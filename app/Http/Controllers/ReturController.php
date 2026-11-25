<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturController extends Controller
{
    public function index(Request $request)
    {
        $pdo = DB::getPdo();

        // Daftar dokumen penerimaan yang bisa diretur
        $penerimaanList = $pdo->query("
            SELECT
                pen.id_penerimaan,
                pen.id_pengadaan,
                pen.created_at AS tanggal_terima,
                v.nama_vendor
            FROM penerimaan pen
            JOIN pengadaan p ON pen.id_pengadaan = p.id_pengadaan
            JOIN vendor v ON p.id_vendor = v.id_vendor
            ORDER BY pen.id_penerimaan DESC
        ")->fetchAll(\PDO::FETCH_OBJ);

        $selectedPenerimaanId = $request->query('id_penerimaan');
        $detailItems = [];

        if ($selectedPenerimaanId) {
            // Ambil item penerimaan beserta sisa qty yang masih boleh diretur
            $stmt = $pdo->prepare("
                SELECT
                    dpr.id_detail_penerimaan,
                    dpr.id_penerimaan,
                    dpr.id_barang,
                    b.nama_barang,
                    s.nama_satuan,
                    dpr.jumlah_terima,
                    COALESCE(ret.total_retur, 0) AS sudah_diretur,
                    (dpr.jumlah_terima - COALESCE(ret.total_retur, 0)) AS sisa_bisa_retur,
                    fn_cek_stok(dpr.id_barang) AS stok_gudang
                FROM detail_penerimaan dpr
                JOIN barang b ON dpr.id_barang = b.id_barang
                LEFT JOIN satuan s ON b.id_satuan = s.id_satuan
                LEFT JOIN (
                    SELECT id_detail_penerimaan, SUM(jumlah) AS total_retur
                    FROM detail_retur
                    GROUP BY id_detail_penerimaan
                ) AS ret ON ret.id_detail_penerimaan = dpr.id_detail_penerimaan
                WHERE dpr.id_penerimaan = :id_pen
                  AND (dpr.jumlah_terima - COALESCE(ret.total_retur, 0)) > 0
            ");
            $stmt->execute([':id_pen' => $selectedPenerimaanId]);
            $detailItems = $stmt->fetchAll(\PDO::FETCH_OBJ);
        }

        // Riwayat Retur
        $riwayat = $pdo->query("
            SELECT
                r.id_retur,
                r.dikembalikan_pada AS tanggal_retur,
                r.id_penerimaan,
                v.nama_vendor,
                u.username AS diproses_oleh
            FROM retur r
            JOIN penerimaan pen ON r.id_penerimaan = pen.id_penerimaan
            JOIN pengadaan p ON pen.id_pengadaan = p.id_pengadaan
            JOIN vendor v ON p.id_vendor = v.id_vendor
            LEFT JOIN user u ON r.id_user = u.id_user
            ORDER BY r.id_retur DESC
        ")->fetchAll(\PDO::FETCH_OBJ);

        return view('retur.index', compact('penerimaanList', 'selectedPenerimaanId', 'detailItems', 'riwayat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_penerimaan' => 'required|integer',
            'items'         => 'required|array',
        ]);

        $idPenerimaan = (int) $request->id_penerimaan;
        $idUser = session('user_id', session('id_user', 1));
        $pdo = DB::getPdo();

        $validItems = [];
        foreach ($request->items as $idDetailPenerimaan => $row) {
            $qty = (int) ($row['jumlah_retur'] ?? 0);
            $maxRetur = (int) ($row['sisa_max'] ?? 0);
            $alasan = trim($row['alasan'] ?? '');

            if ($qty > 0) {
                if ($qty > $maxRetur) {
                    return back()->with('error', "Jumlah retur melebihi sisa item penerimaan ($maxRetur).");
                }
                if (empty($alasan)) {
                    return back()->with('error', "Alasan retur wajib diisi untuk item yang diretur.");
                }
                $validItems[] = [
                    'id_detail_penerimaan' => (int) $idDetailPenerimaan,
                    'id_barang'            => $row['id_barang'],
                    'jumlah'               => $qty,
                    'alasan'               => $alasan,
                ];
            }
        }

        if (empty($validItems)) {
            return back()->with('error', 'Masukkan minimal 1 item dengan jumlah retur lebih dari 0.');
        }

        try {
            $pdo->beginTransaction();

            // Validasi stok gudang mencukupi untuk dikembalikan ke vendor
            $stmtStok = $pdo->prepare("SELECT fn_cek_stok(:id) AS stok");
            foreach ($validItems as $item) {
                $stmtStok->execute([':id' => $item['id_barang']]);
                $stokAktual = (int) $stmtStok->fetch(\PDO::FETCH_OBJ)->stok;
                if ($stokAktual < $item['jumlah']) {
                    $pdo->rollBack();
                    return back()->with('error', "Stok gudang untuk {$item['id_barang']} tidak mencukupi untuk diretur (Sisa stok: {$stokAktual}).");
                }
            }

            // 1. Set @id_retur_baru = 0 agar item pertama otomatis membuat header retur di SP
            $pdo->exec("SET @id_retur_baru = 0");

            // 2. Panggil sp_simpan_retur (Pola INOUT per-item + Trigger stok keluar 'R')
            $stmt = $pdo->prepare("
                CALL sp_simpan_retur(:id_penerimaan, :id_user, :id_detail_penerimaan, :jumlah, :alasan, @id_retur_baru)
            ");

            foreach ($validItems as $item) {
                $stmt->execute([
                    ':id_penerimaan'        => $idPenerimaan,
                    ':id_user'              => $idUser,
                    ':id_detail_penerimaan' => $item['id_detail_penerimaan'],
                    ':jumlah'               => $item['jumlah'],
                    ':alasan'               => $item['alasan'],
                ]);
                $stmt->closeCursor();
            }

            $idReturBaru = $pdo->query("SELECT @id_retur_baru AS id")->fetch(\PDO::FETCH_OBJ)->id;

            $pdo->commit();

            return redirect()->route('retur.nota', $idReturBaru)
                ->with('success', 'Transaksi Retur berhasil disimpan & stok gudang otomatis berkurang!');
        } catch (\Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return back()->with('error', 'Gagal memproses retur: ' . $e->getMessage());
        }
    }

    public function nota($id)
    {
        $pdo = DB::getPdo();

        $stmt = $pdo->prepare("
            SELECT
                r.id_retur,
                r.dikembalikan_pada AS tanggal_retur,
                r.id_penerimaan,
                pen.id_pengadaan,
                v.nama_vendor,
                u.username AS diproses_oleh
            FROM retur r
            JOIN penerimaan pen ON r.id_penerimaan = pen.id_penerimaan
            JOIN pengadaan p ON pen.id_pengadaan = p.id_pengadaan
            JOIN vendor v ON p.id_vendor = v.id_vendor
            LEFT JOIN user u ON r.id_user = u.id_user
            WHERE r.id_retur = :id
            LIMIT 1
        ");
        $stmt->execute([':id' => $id]);
        $header = $stmt->fetch(\PDO::FETCH_OBJ);

        if (!$header) {
            abort(404, 'Nota Retur tidak ditemukan.');
        }

        $stmtDet = $pdo->prepare("
            SELECT
                dr.id_detail_retur,
                dr.jumlah,
                dr.alasan,
                b.id_barang,
                b.nama_barang,
                s.nama_satuan,
                dpr.harga_satuan_terima
            FROM detail_retur dr
            JOIN detail_penerimaan dpr ON dr.id_detail_penerimaan = dpr.id_detail_penerimaan
            JOIN barang b ON dpr.id_barang = b.id_barang
            LEFT JOIN satuan s ON b.id_satuan = s.id_satuan
            WHERE dr.id_retur = :id
        ");
        $stmtDet->execute([':id' => $id]);
        $details = $stmtDet->fetchAll(\PDO::FETCH_OBJ);

        return view('retur.nota', compact('header', 'details'));
    }
}
