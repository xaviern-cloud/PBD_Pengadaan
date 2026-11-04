<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;

class MasterController extends Controller
{
    private function getPdo()
    {
        return DB::getPdo();
    }
    // 1. Filter Master Barang
    public function barang(Request $request)
    {
        $status = $request->get('status', 'All'); // Default 'All'
        $pdo    = $this->getPdo();

        if ($status && $status !== 'All') {
            $stmt   = $pdo->prepare("Select * From v_barang WHERE status = :status ORDER BY  id_barang ASC");
            $stmt->execute([':status' => $status]);
        } else {
            $stmt   =$pdo->prepare("Select * From v_barang ORDER BY id_barang ASC");
            $stmt->execute();
        }

        $barang = $stmt->fetchAll(PDO::FETCH_OBJ);
        return view('master.barang', compact('barang', 'status'));
    }

    // 2. Filter Master Satuan
    public function satuan(Request $request)
    {
        $status = $request->get('status', 'All');
        $pdo   =$this->getPdo();

        if ($status && $status !== 'All') {
            $stmt = $pdo->prepare("Select * from v_satuan where status = :status ORDER BY id_satuan ASC");
            $stmt->execute([':status' => $status]);
        } else {
            $stmt = $pdo->prepare("Select * from v_satuan ORDER BY id_satuan ASC");
            $stmt->execute();

        }

        $satuan = $stmt->fetchAll(PDO::FETCH_OBJ);
        return view('master.satuan', compact('satuan', 'status'));
    }

    // 3. Filter Master Vendor
    public function vendor(Request $request)
    {
        $Status = $request->get('Status', 'All');
        $pdo   =$this->getPdo();

        if ($Status && $Status !== 'All') {
            $stmt = $pdo->prepare("Select * From v_vendor where status = :status ORDER BY id_vendor ASC");
            $stmt->execute([':status'=> $Status]);
        } else {
            $stmt = $pdo->prepare("Select * From v_vendor ORDER BY id_vendor ASC");
            $stmt->execute();
        }

        $vendor = $stmt->fetchAll(PDO::FETCH_OBJ);
        return view('master.vendor', compact('vendor', 'Status'));
    }

    public function storeVendor(Request $request)
    {
        $request->validate([
            'nama_vendor' => 'required|string|max:255',
            'badan_hukum' => 'required|in:P,C,U', // P = PT, C = CV, U = UD
        ]);

        $pdo=$this->getPdo();
        $stmt=$pdo->prepare("INSERT INTO vendor (nama_vendor, badan_hukum, status) VALUES (:nama_vendor, :badan_hukum, 'A')");
        $stmt->execute([
            'nama_vendor' => $request->nama_vendor,
            'badan_hukum' => $request->badan_hukum,
            'status'      => 'A', // Default Aktif ('A' atau '1')
        ]);

        return redirect()->back()->with('success', 'Vendor berhasil ditambahkan!');
    }

    // Edit Vendor
    public function updateVendor(Request $request, $id)
    {
        $request->validate([
            'nama_vendor' => 'required|string|max:255',
            'badan_hukum' => 'required|in:P,C,U',
            'status'      => 'required|in:A,N', // A = Aktif, N = Non-Aktif
        ]);

        $pdo=$this->getPdo();
        $stmt=$pdo->prepare('UPDATE vendor SET nama_vendor = :nama_vendor, badan_hukum = :badan_hukum, status = :status WHERE id_vendor = :id');
        $stmt->execute([
            ':nama_vendor' => $request->nama_vendor,
            ':badan_hukum' => $request->badan_hukum,
            ':status'      => $request->status,
            ':id'          => $id,
        ]);

        return redirect()->back()->with('success', 'Data vendor berhasil diperbarui!');
    }

    // Hapus Vendor
    public function destroyVendor($id)
    {
        $pdo = $this->getPdo();
        $stmt = $pdo->prepare('DELETE FROM vendor WHERE id_vendor = :id');
        $stmt->execute([':id'=> $id]);
        return redirect()->back()->with('success', 'Vendor berhasil dihapus!');
    }

    // 4. Filter Master Margin
    public function margin(Request $request)
    {
        $status = $request->get('status', 'All');
        $pdo = $this->getPdo();

        if ($status && $status !== 'All') {
            $stmt   =$pdo->prepare('Select * from v_margin_penjualan where status = :status ORDER BY id_margin_penjualan ASC');
            $stmt->execute([':status'=>$status]);
        } else {
            $stmt   =$pdo->prepare('Select * From v_margin_penjualan ORDER BY id_margin_penjualan ASC');
            $stmt->execute();
        }

        $margin = $stmt->fetchAll(PDO::FETCH_OBJ);
        return view('master.margin', compact('margin', 'status'));
    }

    public function user()
    {
        $pdo = $this->getPdo();
        $stmt= $pdo->prepare('Select * From v_user ORDER BY id_user ASC');
        $stmt->execute();
        $user = $stmt->fetchAll(PDO::FETCH_OBJ);
        return view('master.user', compact('user'));
    }

    public function role()
    {
        $pdo = $this->getPdo();
        $stmt=$pdo->prepare('Select * From v_role ORDER BY id_role ASC');
        $stmt->execute();
        $role = $stmt->fetchAll(PDO::FETCH_OBJ);
        return view('master.role', compact('role'));
    }

    public function gabungan()
    {
        $pdo=$this->getPDO();

        $barang = $pdo->query('Select * From v_barang')->FetchAll(PDO::FETCH_OBJ);
        $satuan = $pdo->query('Select * From v_satuan')->FetchAll(PDO::FETCH_OBJ);
        $vendor = $pdo->query('Select * From v_vendor')->FetchAll(PDO::FETCH_OBJ);
        $user   = $pdo->query('Select * From v_user')->FetchAll(PDO::FETCH_OBJ);
        $role   = $pdo->query('Select * From v_role')->FetchAll(PDO::FETCH_OBJ);
        $margin = $pdo->query('Select * From v_margin_penjualan')->FetchAll(PDO::FETCH_OBJ);

        // Oper semua variabel ke view master.gabungan
        return view('master.gabungan', compact(
            'barang',
            'satuan',
            'vendor',
            'user',
            'role',
            'margin'
        ));
    }
}
