<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengadaanController extends Controller
{
    public function index()
    {
        $pdo = DB::getPdo();

        $barangs =$pdo->query("SELECT * FROM v_barang WHERE status = 'Aktif' ORDER BY id_barang ASC")->fetchAll(\PDO::FETCH_OBJ);
        $vendors =$pdo->query("SELECT * FROM v_vendor WHERE status = 'Aktif' ORDER BY nama_vendor ASC")->fetchAll(\PDO::FETCH_OBJ);
        $pengadaan =$pdo->query("
            SELECT
                p.id_pengadaan,
                p.timestamp AS tanggal,
                v.nama_vendor,
                u.username AS dibuat_oleh,
                p.subtotal,
                p.ppn,
                p.total_nilai,
                p.status AS kode_status,
                CASE
                    WHEN p.status = 'F' THEN 'Finished'
                    WHEN p.status = 'S' THEN 'Sebagian'
                    ELSE 'Pending'
                END AS status
            FROM pengadaan p
            LEFT JOIN vendor v ON p.id_vendor = v.id_vendor
            LEFT JOIN user u ON p.id_user = u.id_user
            ORDER BY p.id_pengadaan DESC
        ")->fetchAll(\PDO::FETCH_OBJ);

        $cart = session('po_cart', []);

        return view('pengadaan.index', compact('barangs', 'vendors', 'pengadaan', 'cart'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'id_barang'    => 'required|string',
            'harga_satuan' => 'required|numeric|min:1',
            'jumlah'       => 'required|integer|min:1',
        ]);

        $pdo = DB::getPdo();
        $stmt =$pdo->prepare("SELECT id_barang, nama_barang, nama_satuan FROM v_barang WHERE id_barang = :id LIMIT 1");
        $stmt->execute(['id' =>$request->id_barang]);
        $barang =$stmt->fetch(\PDO::FETCH_OBJ);

        if (!$barang) {
            return back()->with('error', 'Barang tidak ditemukan.');
        }

        $cart = session('po_cart', []);
        $id =$barang->id_barang;

        if (isset($cart[$id])) {$cart[$id]['jumlah'] += (int)$request->jumlah;
            $cart[$id]['harga_satuan'] = (int)$request->harga_satuan;
            $cart[$id]['sub_total'] = $cart[$id]['jumlah'] * $cart[$id]['harga_satuan'];
        } else {
            $cart[$id] = [
                'id_barang'    => $barang->id_barang,
                'nama_barang'  => $barang->nama_barang,
                'nama_satuan'  => $barang->nama_satuan,
                'harga_satuan' => (int) $request->harga_satuan,
                'jumlah'       => (int) $request->jumlah,
                'sub_total'    => (int) $request->jumlah * (int)$request->harga_satuan,
            ];
        }

        session(['po_cart' => $cart]);
        return back()->with('success', 'Item ditambahkan ke keranjang PO.');
    }

    public function removeFromCart($id_barang)
    {
        $cart = session('po_cart', []);
        unset($cart[$id_barang]);
        session(['po_cart' => $cart]);

        return back()->with('success', 'Item dihapus dari keranjang PO.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_vendor' => 'required|integer',
        ]);

        $cart = session('po_cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Keranjang PO masih kosong!');
        }

        $idUser = session('id_user', 1);
        $pdo = DB::getPdo();

        try {
            $pdo->beginTransaction();

            // 1. Set @id_po_baru = 0 agar item pertama otomatis membuat header pengadaan di SP
            $pdo->exec("SET @id_po_baru = 0");

            // 2. Siapkan panggilan sp_simpan_pengadaan (Pola INOUT per-item)
            $stmt = $pdo->prepare("
                CALL sp_simpan_pengadaan(:id_user, :id_vendor, :id_barang, :jumlah, :harga, @id_po_baru)
            ");

            // 3. Looping setiap item di keranjang (SP otomatis insert detail & update subtotal + PPN header)
            foreach ($cart as $item) {
                $stmt->execute([
                    ':id_user'   => $idUser,
                    ':id_vendor' => $request->id_vendor,
                    ':id_barang' => $item['id_barang'],
                    ':jumlah'    => $item['jumlah'],
                    ':harga'     => $item['harga_satuan'],
                ]);
                $stmt->closeCursor();
            }

            // 4. Ambil ID Pengadaan yang baru terbentuk dari parameter INOUT
            $idPoBaru = $pdo->query("SELECT @id_po_baru AS id")->fetch(\PDO::FETCH_OBJ)->id;

            $pdo->commit();
            session()->forget('po_cart');

            return redirect()->route('pengadaan.nota', $idPoBaru)->with('success', 'Dokumen Pengadaan (PO) berhasil disimpan!');
        } catch (\Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return back()->with('error', 'Gagal menyimpan PO: ' . $e->getMessage());
        }
    }

    public function nota($id)
    {
        $pdo = DB::getPdo();

        $stmt =$pdo->prepare("
            SELECT
                p.id_pengadaan,
                p.timestamp AS tanggal,
                v.nama_vendor,
                u.username AS dibuat_oleh,
                p.subtotal,
                p.ppn,
                p.total_nilai,
                CASE
                    WHEN p.status = 'F' THEN 'Finished'
                    WHEN p.status = 'S' THEN 'Sebagian'
                    ELSE 'Pending'
                END AS status
            FROM pengadaan p
            LEFT JOIN vendor v ON p.id_vendor = v.id_vendor
            LEFT JOIN user u ON p.id_user = u.id_user
            WHERE p.id_pengadaan = :id
            LIMIT 1
        ");
        $stmt->execute(['id' =>$id]);
        $header =$stmt->fetch(\PDO::FETCH_OBJ);

        if (!$header) {
            abort(404, 'Nota Pengadaan tidak ditemukan.');
        }

        $stmtDet =$pdo->prepare("
            SELECT dp.*, b.nama_barang, s.nama_satuan
            FROM detail_pengadaan dp
            JOIN barang b ON dp.id_barang = b.id_barang
            LEFT JOIN satuan s ON b.id_satuan = s.id_satuan
            WHERE dp.id_pengadaan = :id
        ");
        $stmtDet->execute(['id' =>$id]);
        $details =$stmtDet->fetchAll(\PDO::FETCH_OBJ);

        return view('pengadaan.nota', compact('header', 'details'));
    }
}
