<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        $pdo = DB::getPdo();

        $margins = $pdo->query("SELECT id_margin_penjualan, persen FROM margin_penjualan WHERE status = 1 ORDER BY id_margin_penjualan ASC")->fetchAll(\PDO::FETCH_OBJ);
        $selectedMargin = $request->query('idmargin', $margins[0]->id_margin_penjualan ?? 1);

        // Panggil SQL Function get_harga_jual() & fn_cek_stok() langsung di SELECT
        $stmt = $pdo->prepare("
            SELECT
                b.id_barang,
                b.nama_barang,
                s.nama_satuan,
                b.harga AS harga_modal,
                get_harga_jual(b.id_barang, :idmargin) AS harga_jual,
                fn_cek_stok(b.id_barang) AS stok
            FROM barang b
            LEFT JOIN satuan s ON b.id_satuan = s.id_satuan
            WHERE b.status = 1
            ORDER BY b.id_barang ASC
        ");
        $stmt->execute(['idmargin' => $selectedMargin]);
        $barangs = $stmt->fetchAll(\PDO::FETCH_OBJ);

        $riwayat = $pdo->query("
            SELECT
                pj.id_penjualan,
                pj.created_at AS tanggal,
                u.username AS kasir,
                m.persen AS margin_persen,
                pj.total_nilai
            FROM penjualan pj
            LEFT JOIN user u ON pj.id_user = u.id_user
            LEFT JOIN margin_penjualan m ON pj.id_margin_penjualan = m.id_margin_penjualan
            ORDER BY pj.id_penjualan DESC
        ")->fetchAll(\PDO::FETCH_OBJ);

        $cart = session('pos_cart', []);

        return view('penjualan.index', compact('margins', 'selectedMargin', 'barangs', 'riwayat', 'cart'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'id_barang' => 'required|string',
            'idmargin'  => 'required|integer',
            'jumlah'    => 'required|integer|min:1',
        ]);

        $pdo = DB::getPdo();
        $stmt = $pdo->prepare("
            SELECT
                b.id_barang,
                b.nama_barang,
                s.nama_satuan,
                get_harga_jual(b.id_barang, :idmargin) AS harga_jual,
                fn_cek_stok(b.id_barang) AS stok
            FROM barang b
            LEFT JOIN satuan s ON b.id_satuan = s.id_satuan
            WHERE b.id_barang = :id_barang
            LIMIT 1
        ");
        $stmt->execute([
            'idmargin'  => $request->idmargin,
            'id_barang' => $request->id_barang,
        ]);
        $barang = $stmt->fetch(\PDO::FETCH_OBJ);

        if (!$barang) {
            return back()->with('error', 'Barang tidak ditemukan.');
        }

        $cart = session('pos_cart', []);
        $id = $barang->id_barang;
        $qtyBaru = ($cart[$id]['jumlah'] ?? 0) + (int) $request->jumlah;

        if ($qtyBaru > $barang->stok) {
            return back()->with('error', "Stok {$barang->nama_barang} tidak mencukupi (Sisa stok: {$barang->stok}).");
        }

        $cart[$id] = [
            'id_barang'   => $barang->id_barang,
            'nama_barang' => $barang->nama_barang,
            'nama_satuan' => $barang->nama_satuan,
            'harga_jual'  => (int) $barang->harga_jual,
            'jumlah'      => $qtyBaru,
            'subtotal'    => $qtyBaru * (int) $barang->harga_jual,
        ];

        session(['pos_cart' => $cart]);
        return redirect()->route('penjualan.index', ['idmargin' => $request->idmargin])->with('success', 'Item masuk keranjang kasir.');
    }

    public function removeFromCart($id_barang)
    {
        $cart = session('pos_cart', []);
        unset($cart[$id_barang]);
        session(['pos_cart' => $cart]);

        return back()->with('success', 'Item dihapus dari keranjang.');
    }

    public function clearCart()
    {
        session()->forget('pos_cart');
        return back()->with('success', 'Keranjang kasir dikosongkan.');
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'idmargin' => 'required|integer',
        ]);

        $cart = session('pos_cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Keranjang belanja masih kosong!');
        }

        $subtotal = array_sum(array_column($cart, 'subtotal'));
        $idUser = session('id_user', 1);
        $pdo = DB::getPdo();

        try {
            $pdo->beginTransaction();

            // Validasi ulang stok terakhir via fn_cek_stok()
            $stmtStok = $pdo->prepare("SELECT fn_cek_stok(:id) AS stok");
            foreach ($cart as $item) {
                $stmtStok->execute(['id' => $item['id_barang']]);
                $stokAktual = (int) $stmtStok->fetch(\PDO::FETCH_OBJ)->stok;
                if ($stokAktual < $item['jumlah']) {
                    $pdo->rollBack();
                    return back()->with('error', "Stok {$item['nama_barang']} berubah dan tidak cukup (Sisa: {$stokAktual}).");
                }
            }

            // Panggil SP Simpan Header Penjualan
            $stmt = $pdo->prepare("CALL sp_insert_penjualan(:id_user, :id_margin, :subtotal, @id_jual_baru)");
            $stmt->execute([
                'id_user'   => $idUser,
                'id_margin' => $request->idmargin,
                'subtotal'  => $subtotal,
            ]);
            $stmt->closeCursor();

            $idJualBaru = $pdo->query("SELECT @id_jual_baru AS id")->fetch(\PDO::FETCH_OBJ)->id;

            // Insert Detail Penjualan (Memicu Trigger after_insert_detail_penjualan -> Kartu Stok Keluar 'K')
            $stmtDet = $pdo->prepare("
                INSERT INTO detail_penjualan (harga_satuan, jumlah, subtotal, id_penjualan, id_barang)
                VALUES (:harga_satuan, :jumlah, :subtotal, :id_penjualan, :id_barang)
            ");

            foreach ($cart as $item) {
                $stmtDet->execute([
                    'harga_satuan' => $item['harga_jual'],
                    'jumlah'       => $item['jumlah'],
                    'subtotal'     => $item['subtotal'],
                    'id_penjualan' => $idJualBaru,
                    'id_barang'    => $item['id_barang'],
                ]);
            }

            $pdo->commit();
            session()->forget('pos_cart');

            return redirect()->route('penjualan.nota', $idJualBaru)->with('success', 'Transaksi penjualan berhasil!');
        } catch (\Exception $e) {
            $pdo->rollBack();
            return back()->with('error', 'Checkout gagal: ' . $e->getMessage());
        }
    }

    public function nota($id)
    {
        $pdo = DB::getPdo();

        $stmt = $pdo->prepare("
            SELECT
                pj.id_penjualan,
                pj.created_at AS tanggal,
                pj.subtotal_nilai,
                pj.ppn,
                pj.total_nilai,
                u.username AS kasir
            FROM penjualan pj
            LEFT JOIN user u ON pj.id_user = u.id_user
            WHERE pj.id_penjualan = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        $header = $stmt->fetch(\PDO::FETCH_OBJ);

        if (!$header) {
            abort(404, 'Struk Penjualan tidak ditemukan.');
        }

        $stmtDet = $pdo->prepare("
            SELECT dp.*, b.nama_barang, s.nama_satuan
            FROM detail_penjualan dp
            JOIN barang b ON dp.id_barang = b.id_barang
            LEFT JOIN satuan s ON b.id_satuan = s.id_satuan
            WHERE dp.id_penjualan = :id
        ");
        $stmtDet->execute(['id' => $id]);
        $details = $stmtDet->fetchAll(\PDO::FETCH_OBJ);

        return view('penjualan.nota', compact('header', 'details'));
    }
}
