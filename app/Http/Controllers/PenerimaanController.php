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

            // 1. Panggil SP Simpan Header Penerimaan
            $stmt = $pdo->prepare("CALL sp_insert_penerimaan(:id_po, :id_user, @id_terima_baru)");
            $stmt->execute([
                'id_po'   => $idPengadaan,
                'id_user' => $idUser,
            ]);
            $stmt->closeCursor();

            $idTerimaBaru = $pdo->query("SELECT @id_terima_baru AS id")->fetch(\PDO::FETCH_OBJ)->id;

            // 2. Insert Detail Penerimaan (Memicu Trigger after_insert_detail_penerimaan -> Kartu Stok Masuk 'M')
            $stmtDet = $pdo->prepare("
                INSERT INTO detail_penerimaan (id_penerimaan, id_barang, jumlah_terima, harga_satuan_terima, sub_total_terima)
                VALUES (:id_penerimaan, :id_barang, :jumlah_terima, :harga_satuan, :sub_total)
            ");

            foreach ($validItems as $idBarang => $item) {
                $stmtDet->execute([
                    'id_penerimaan' => $idTerimaBaru,
                    'id_barang'     => $idBarang,
                    'jumlah_terima' => $item['jumlah_terima'],
                    'harga_satuan'  => $item['harga_satuan'],
                    'sub_total'     => $item['jumlah_terima'] * $item['harga_satuan'],
                ]);
            }

            // 3. Cek kelengkapan PO memakai SQL Function fn_pengadaan_selesai()
            $stmtCek = $pdo->prepare("SELECT fn_pengadaan_selesai(:id_po) AS selesai");
            $stmtCek->execute(['id_po' => $idPengadaan]);
            $isSelesai = (int) $stmtCek->fetch(\PDO::FETCH_OBJ)->selesai;

            $statusBaru = $isSelesai === 1 ? 'F' : 'S';
            $stmtUpd = $pdo->prepare("UPDATE pengadaan SET status = :status WHERE id_pengadaan = :id_po");
            $stmtUpd->execute(['status' => $statusBaru, 'id_po' => $idPengadaan]);

            $pdo->commit();

            return redirect()->route('penerimaan.nota', $idTerimaBaru)
                ->with('success', 'Penerimaan dicatat & stok bertambah otomatis! Status PO: ' . ($statusBaru === 'F' ? 'Finished' : 'Sebagian'));
        } catch (\Exception $e) {
            $pdo->rollBack();
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
