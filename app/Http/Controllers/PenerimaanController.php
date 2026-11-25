<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenerimaanController extends Controller
{
    public function index(Request $request)
    {
        $pdo = DB::getPdo();

        // Ambil PO yang belum selesai (status P atau S)
        $poList = $pdo->query("
            SELECT
                p.id_pengadaan,
                p.timestamp,
                v.nama_vendor,
                CASE WHEN p.status = 'S' THEN 'Sebagian' ELSE 'Pending' END AS status
            FROM pengadaan p
            JOIN vendor v ON p.id_vendor = v.id_vendor
            WHERE p.status IN ('P', 'S')
            ORDER BY p.id_pengadaan DESC
        ")->fetchAll(\PDO::FETCH_OBJ);

        $selectedPoId = $request->query('id_pengadaan');
        $remainingItems = [];

        if ($selectedPoId) {
            $stmt = $pdo->prepare("CALL sp_get_detail_pengadaan_remaining(:id)");
            $stmt->execute(['id' => $selectedPoId]);
            $remainingItems = $stmt->fetchAll(\PDO::FETCH_OBJ);
            $stmt->closeCursor();
        }

        $riwayat = $pdo->query("
            SELECT
                pen.id_penerimaan,
                pen.id_pengadaan,
                pen.created_at AS tanggal_terima,
                v.nama_vendor,
                u.username AS diterima_oleh
            FROM penerimaan pen
            JOIN pengadaan p ON pen.id_pengadaan = p.id_pengadaan
            JOIN vendor v ON p.id_vendor = v.id_vendor
            LEFT JOIN user u ON pen.id_user = u.id_user
            ORDER BY pen.id_penerimaan DESC
        ")->fetchAll(\PDO::FETCH_OBJ);

        return view('penerimaan.index', compact('poList', 'selectedPoId', 'remainingItems', 'riwayat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pengadaan' => 'required|integer',
            'items'        => 'required|array',
        ]);

        $idPengadaan = (int) $request->id_pengadaan;
        $idUser = session('id_user', 1);
        $pdo = DB::getPdo();

        // Filter item yang qty terimanya > 0
        $validItems = [];
        foreach ($request->items as $idBarang => $row) {
            $qty = (int) ($row['jumlah_terima'] ?? 0);
            $max = (int) ($row['sisa_max'] ?? 0);
            if ($qty > 0) {
                if ($qty > $max) {
                    return back()->with('error', "Jumlah terima untuk $idBarang melebihi sisa PO ($max).");
                }
                $validItems[$idBarang] = [
                    'jumlah_terima' => $qty,
                    'harga_satuan'  => (int) $row['harga_satuan'],
                ];
            }
        }

        if (empty($validItems)) {
            return back()->with('error', 'Masukkan minimal 1 barang dengan jumlah terima lebih dari 0.');
        }

        try {
            $pdo->beginTransaction();

            // 1. Set @id_terima_baru = 0 agar item pertama otomatis membuat header penerimaan
            $pdo->exec("SET @id_terima_baru = 0");

            // 2. Siapkan panggilan sp_simpan_penerimaan (Pola INOUT per-item)
            $stmt = $pdo->prepare("
                CALL sp_simpan_penerimaan(:id_po, :id_user, :id_barang, :jumlah_terima, :harga, @id_terima_baru)
            ");

            // 3. Looping setiap item (SP otomatis insert detail, trigger stok 'M', & update status PO 'S'/'F')
            foreach ($validItems as $idBarang => $item) {
                $stmt->execute([
                    ':id_po'         => $idPengadaan,
                    ':id_user'       => $idUser,
                    ':id_barang'     => $idBarang,
                    ':jumlah_terima' => $item['jumlah_terima'],
                    ':harga'         => $item['harga_satuan'],
                ]);
                $stmt->closeCursor();
            }

            // 4. Ambil ID Penerimaan yang baru terbentuk dari parameter INOUT
            $idTerimaBaru = $pdo->query("SELECT @id_terima_baru AS id")->fetch(\PDO::FETCH_OBJ)->id;

            // 5. Ambil status PO terbaru (hasil update otomatis SP) untuk pesan notifikasi
            $stmtStatus = $pdo->prepare("SELECT status FROM pengadaan WHERE id_pengadaan = :id_po LIMIT 1");
            $stmtStatus->execute([':id_po' => $idPengadaan]);
            $statusBaru = $stmtStatus->fetch(\PDO::FETCH_OBJ)->status ?? 'S';

            $pdo->commit();

            return redirect()->route('penerimaan.nota', $idTerimaBaru)
                ->with('success', 'Penerimaan dicatat & stok bertambah otomatis! Status PO: ' . ($statusBaru === 'F' ? 'Finished' : 'Sebagian'));
        } catch (\Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return back()->with('error', 'Gagal memproses penerimaan: ' . $e->getMessage());
        }
    }

    public function nota($id)
    {
        $pdo = DB::getPdo();

        $stmt = $pdo->prepare("
            SELECT
                pen.id_penerimaan,
                pen.id_pengadaan,
                pen.created_at AS tanggal_terima,
                v.nama_vendor,
                u.username AS diterima_oleh
            FROM penerimaan pen
            JOIN pengadaan p ON pen.id_pengadaan = p.id_pengadaan
            JOIN vendor v ON p.id_vendor = v.id_vendor
            LEFT JOIN user u ON pen.id_user = u.id_user
            WHERE pen.id_penerimaan = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        $header = $stmt->fetch(\PDO::FETCH_OBJ);

        if (!$header) {
            abort(404, 'Nota Penerimaan tidak ditemukan.');
        }

        $stmtDet = $pdo->prepare("
            SELECT dpr.*, b.nama_barang, s.nama_satuan
            FROM detail_penerimaan dpr
            JOIN barang b ON dpr.id_barang = b.id_barang
            LEFT JOIN satuan s ON b.id_satuan = s.id_satuan
            WHERE dpr.id_penerimaan = :id
        ");
        $stmtDet->execute(['id' => $id]);
        $details = $stmtDet->fetchAll(\PDO::FETCH_OBJ);

        return view('penerimaan.nota', compact('header', 'details'));
    }
}
