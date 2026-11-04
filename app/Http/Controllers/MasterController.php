<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;

class MasterController extends Controller
{
    /**
     * Helper privat untuk mendapatkan koneksi PDO Murni dari Laravel
     */
    private function getPdo()
    {
        return DB::getPdo();
    }

    /* =========================================================================
     * 1. MASTER BARANG (Custom ID VARCHAR 'BB-001' via Trigger/SP)
     * ========================================================================= */
    public function barang(Request $request)
    {
        $status = $request->get('status', 'All');
        $pdo    = $this->getPdo();

        if ($status && $status !== 'All') {
            $stmt = $pdo->prepare("SELECT * FROM v_barang WHERE status = :status ORDER BY id_barang ASC");
            $stmt->execute([':status' => $status]);
        } else {
            $stmt = $pdo->prepare("SELECT * FROM v_barang ORDER BY id_barang ASC");
            $stmt->execute();
        }

        $barang = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmtSatuan = $pdo->prepare("SELECT id_satuan, nama_satuan FROM satuan WHERE status = 1 ORDER BY nama_satuan ASC");
        $stmtSatuan->execute();
        $satuanList = $stmtSatuan->fetchAll(PDO::FETCH_OBJ);

        return view('master.barang', compact('barang', 'status', 'satuanList'));
    }

    public function storeBarang(Request $request)
    {
        $request->validate([
            'jenis_barang' => 'required|in:BB,BSJ,BJ,BO,BK',
            'nama_barang'  => 'required|string|max:255',
            'id_satuan'    => 'required|integer',
            'harga'        => 'required|numeric',
        ]);

        $pdo = $this->getPdo();

        // Opsi 1: Panggil Stored Procedure (Trigger BEFORE INSERT otomatis generate ID "BB-001")
        $stmt = $pdo->prepare("CALL sp_tambah_barang(:jenis, :nama, :id_satuan, :harga, @id_baru)");
        $stmt->execute([
            ':jenis'     => $request->jenis_barang,
            ':nama'      => $request->nama_barang,
            ':id_satuan' => $request->id_satuan,
            ':harga'     => $request->harga,
        ]);

        return redirect()->back()->with('success', 'Barang berhasil ditambahkan!');
    }

    public function updateBarang(Request $request, $id)
    {
        $request->validate([
            'jenis_barang' => 'required|in:BB,BSJ,BJ,BO,BK',
            'nama_barang'  => 'required|string|max:255',
            'id_satuan'    => 'required|integer',
            'harga'        => 'required|numeric',
            'status'       => 'required|in:1,0',
        ]);

        $pdo = $this->getPdo();
        $stmt = $pdo->prepare("
            UPDATE barang
            SET jenis_barang = :jenis,
                nama_barang = :nama,
                id_satuan = :id_satuan,
                harga = :harga,
                status = :status
            WHERE id_barang = :id
        ");
        $stmt->execute([
            ':jenis'     => $request->jenis_barang,
            ':nama'      => $request->nama_barang,
            ':id_satuan' => $request->id_satuan,
            ':harga'     => $request->harga,
            ':status'    => $request->status,
            ':id'        => $id, // Parameter ID berupa String (cth: 'BB-001')
        ]);

        return redirect()->back()->with('success', 'Data barang berhasil diperbarui!');
    }

    public function destroyBarang($id)
    {
        $pdo = $this->getPdo();
        $stmt = $pdo->prepare("DELETE FROM barang WHERE id_barang = :id");
        $stmt->execute([':id' => $id]);

        return redirect()->back()->with('success', 'Barang berhasil dihapus!');
    }


    /* =========================================================================
     * 2. MASTER SATUAN
     * ========================================================================= */
    public function satuan(Request $request)
    {
        $status = $request->get('status', 'All');
        $pdo    = $this->getPdo();

        if ($status && $status !== 'All') {
            $stmt = $pdo->prepare("SELECT * FROM v_satuan WHERE status = :status ORDER BY id_satuan ASC");
            $stmt->execute([':status' => $status]);
        } else {
            $stmt = $pdo->prepare("SELECT * FROM v_satuan ORDER BY id_satuan ASC");
            $stmt->execute();
        }

        $satuan = $stmt->fetchAll(PDO::FETCH_OBJ);
        return view('master.satuan', compact('satuan', 'status'));
    }

    public function storeSatuan(Request $request)
    {
        $request->validate([
            'nama_satuan' => 'required|string|max:45',
        ]);

        $pdo = $this->getPdo();
        $stmt = $pdo->prepare("INSERT INTO satuan (nama_satuan, status) VALUES (:nama, 1)");
        $stmt->execute([':nama' => $request->nama_satuan]);

        return redirect()->back()->with('success', 'Satuan berhasil ditambahkan!');
    }

    public function updateSatuan(Request $request, $id)
    {
        $request->validate([
            'nama_satuan' => 'required|string|max:45',
            'status'      => 'required|in:1,0',
        ]);

        $pdo = $this->getPdo();
        $stmt = $pdo->prepare("UPDATE satuan SET nama_satuan = :nama, status = :status WHERE id_satuan = :id");
        $stmt->execute([
            ':nama'   => $request->nama_satuan,
            ':status' => $request->status,
            ':id'     => $id,
        ]);

        return redirect()->back()->with('success', 'Data satuan berhasil diperbarui!');
    }

    public function destroySatuan($id)
    {
        $pdo = $this->getPdo();
        $stmt = $pdo->prepare("DELETE FROM satuan WHERE id_satuan = :id");
        $stmt->execute([':id' => $id]);

        return redirect()->back()->with('success', 'Satuan berhasil dihapus!');
    }


    /* =========================================================================
     * 3. MASTER VENDOR
     * ========================================================================= */
    public function vendor(Request $request)
    {
        $status = $request->get('status', 'All');
        $pdo    = $this->getPdo();

        if ($status && $status !== 'All') {
            $stmt = $pdo->prepare("SELECT * FROM v_vendor WHERE status = :status ORDER BY id_vendor ASC");
            $stmt->execute([':status' => $status]);
        } else {
            $stmt = $pdo->prepare("SELECT * FROM v_vendor ORDER BY id_vendor ASC");
            $stmt->execute();
        }

        $vendor = $stmt->fetchAll(PDO::FETCH_OBJ);
        return view('master.vendor', compact('vendor', 'status'));
    }

    public function storeVendor(Request $request)
    {
        $request->validate([
            'nama_vendor' => 'required|string|max:255',
            'badan_hukum' => 'required|in:P,C,U',
        ]);

        $pdo = $this->getPdo();
        $stmt = $pdo->prepare("INSERT INTO vendor (nama_vendor, badan_hukum, status) VALUES (:nama_vendor, :badan_hukum, 'A')");
        $stmt->execute([
            ':nama_vendor' => $request->nama_vendor,
            ':badan_hukum' => $request->badan_hukum,
        ]);

        return redirect()->back()->with('success', 'Vendor berhasil ditambahkan!');
    }

    public function updateVendor(Request $request, $id)
    {
        $request->validate([
            'nama_vendor' => 'required|string|max:255',
            'badan_hukum' => 'required|in:P,C,U',
            'status'      => 'required|in:A,N',
        ]);

        $pdo = $this->getPdo();
        $stmt = $pdo->prepare("UPDATE vendor SET nama_vendor = :nama_vendor, badan_hukum = :badan_hukum, status = :status WHERE id_vendor = :id");
        $stmt->execute([
            ':nama_vendor' => $request->nama_vendor,
            ':badan_hukum' => $request->badan_hukum,
            ':status'      => $request->status,
            ':id'          => $id,
        ]);

        return redirect()->back()->with('success', 'Data vendor berhasil diperbarui!');
    }

    public function destroyVendor($id)
    {
        $pdo = $this->getPdo();
        $stmt = $pdo->prepare("DELETE FROM vendor WHERE id_vendor = :id");
        $stmt->execute([':id' => $id]);

        return redirect()->back()->with('success', 'Vendor berhasil dihapus!');
    }


    /* =========================================================================
     * 4. MASTER MARGIN PENJUALAN
     * ========================================================================= */
    public function margin(Request $request)
    {
        $status = $request->get('status', 'All');
        $pdo    = $this->getPdo();

        if ($status && $status !== 'All') {
            $stmt = $pdo->prepare("SELECT * FROM v_margin_penjualan WHERE status = :status ORDER BY id_margin_penjualan ASC");
            $stmt->execute([':status' => $status]);
        } else {
            $stmt = $pdo->prepare("SELECT * FROM v_margin_penjualan ORDER BY id_margin_penjualan ASC");
            $stmt->execute();
        }

        $margin = $stmt->fetchAll(PDO::FETCH_OBJ);
        return view('master.margin', compact('margin', 'status'));
    }

    public function storeMargin(Request $request)
    {
        $request->validate([
            'persen'  => 'required|numeric',
            'id_user' => 'required|integer',
        ]);

        $pdo = $this->getPdo();
        $stmt = $pdo->prepare("
            INSERT INTO margin_penjualan (persen, status, id_user, created_at, updated_at)
            VALUES (:persen, 1, :id_user, NOW(), NOW())
        ");
        $stmt->execute([
            ':persen'  => $request->persen,
            ':id_user' => $request->id_user,
        ]);

        return redirect()->back()->with('success', 'Margin penjualan berhasil ditambahkan!');
    }

    public function updateMargin(Request $request, $id)
    {
        $request->validate([
            'persen'  => 'required|numeric',
            'status'  => 'required|in:1,0',
            'id_user' => 'required|integer',
        ]);

        $pdo = $this->getPdo();
        $stmt = $pdo->prepare("
            UPDATE margin_penjualan
            SET persen = :persen, status = :status, id_user = :id_user, updated_at = NOW()
            WHERE id_margin_penjualan = :id
        ");
        $stmt->execute([
            ':persen'  => $request->persen,
            ':status'  => $request->status,
            ':id_user' => $request->id_user,
            ':id'      => $id,
        ]);

        return redirect()->back()->with('success', 'Margin penjualan berhasil diperbarui!');
    }

    public function destroyMargin($id)
    {
        $pdo = $this->getPdo();
        $stmt = $pdo->prepare("DELETE FROM margin_penjualan WHERE id_margin_penjualan = :id");
        $stmt->execute([':id' => $id]);

        return redirect()->back()->with('success', 'Margin penjualan berhasil dihapus!');
    }


    /* =========================================================================
     * 5. MASTER USER
     * ========================================================================= */
    public function user()
    {
        $pdo = $this->getPdo();
        $stmt = $pdo->prepare("SELECT * FROM v_user ORDER BY id_user ASC");
        $stmt->execute();

        $user = $stmt->fetchAll(PDO::FETCH_OBJ);
        return view('master.user', compact('user'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:45|unique:user,username',
            'password' => 'required|string|min:4',
            'id_role'  => 'required|integer',
        ]);

        $pdo = $this->getPdo();
        $stmt = $pdo->prepare("INSERT INTO user (username, password, id_role) VALUES (:username, :password, :id_role)");
        $stmt->execute([
            ':username' => $request->username,
            ':password' => bcrypt($request->password),
            ':id_role'  => $request->id_role,
        ]);

        return redirect()->back()->with('success', 'User berhasil ditambahkan!');
    }

    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|string|max:45',
            'id_role'  => 'required|integer',
        ]);

        $pdo = $this->getPdo();

        if ($request->filled('password')) {
            $stmt = $pdo->prepare("UPDATE user SET username = :username, password = :password, id_role = :id_role WHERE id_user = :id");
            $stmt->execute([
                ':username' => $request->username,
                ':password' => bcrypt($request->password),
                ':id_role'  => $request->id_role,
                ':id'       => $id,
            ]);
        } else {
            $stmt = $pdo->prepare("UPDATE user SET username = :username, id_role = :id_role WHERE id_user = :id");
            $stmt->execute([
                ':username' => $request->username,
                ':id_role'  => $request->id_role,
                ':id'       => $id,
            ]);
        }

        return redirect()->back()->with('success', 'Data user berhasil diperbarui!');
    }

    public function destroyUser($id)
    {
        $pdo = $this->getPdo();
        $stmt = $pdo->prepare("DELETE FROM user WHERE id_user = :id");
        $stmt->execute([':id' => $id]);

        return redirect()->back()->with('success', 'User berhasil dihapus!');
    }


    /* =========================================================================
     * 6. MASTER ROLE
     * ========================================================================= */
    public function role()
    {
        $pdo = $this->getPdo();
        $stmt = $pdo->prepare("SELECT * FROM v_role ORDER BY id_role ASC");
        $stmt->execute();

        $role = $stmt->fetchAll(PDO::FETCH_OBJ);
        return view('master.role', compact('role'));
    }

    public function storeRole(Request $request)
    {
        $request->validate([
            'nama_role' => 'required|string|max:100',
        ]);

        $pdo = $this->getPdo();
        $stmt = $pdo->prepare("INSERT INTO role (nama_role) VALUES (:nama_role)");
        $stmt->execute([':nama_role' => $request->nama_role]);

        return redirect()->back()->with('success', 'Role berhasil ditambahkan!');
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'nama_role' => 'required|string|max:100',
        ]);

        $pdo = $this->getPdo();
        $stmt = $pdo->prepare("UPDATE role SET nama_role = :nama_role WHERE id_role = :id");
        $stmt->execute([
            ':nama_role' => $request->nama_role,
            ':id'        => $id,
        ]);

        return redirect()->back()->with('success', 'Role berhasil diperbarui!');
    }

    public function destroyRole($id)
    {
        $pdo = $this->getPdo();
        $stmt = $pdo->prepare("DELETE FROM role WHERE id_role = :id");
        $stmt->execute([':id' => $id]);

        return redirect()->back()->with('success', 'Role berhasil dihapus!');
    }


    /* =========================================================================
     * 7. VIEW GABUNGAN SELURUH MASTER DATA
     * ========================================================================= */
    public function gabungan()
    {
        $pdo = $this->getPdo();

        $barang = $pdo->query("SELECT * FROM v_barang")->fetchAll(PDO::FETCH_OBJ);
        $satuan = $pdo->query("SELECT * FROM v_satuan")->fetchAll(PDO::FETCH_OBJ);
        $vendor = $pdo->query("SELECT * FROM v_vendor")->fetchAll(PDO::FETCH_OBJ);
        $user   = $pdo->query("SELECT * FROM v_user")->fetchAll(PDO::FETCH_OBJ);
        $role   = $pdo->query("SELECT * FROM v_role")->fetchAll(PDO::FETCH_OBJ);
        $margin = $pdo->query("SELECT * FROM v_margin_penjualan")->fetchAll(PDO::FETCH_OBJ);

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
