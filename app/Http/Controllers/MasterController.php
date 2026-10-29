<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangView;
use App\Models\SatuanView;
use App\Models\Vendor;
use App\Models\VendorView;
use App\Models\UserView;
use App\Models\RoleView;
use App\Models\MarginPenjualanView;

class MasterController extends Controller
{
    // 1. Filter Master Barang
    public function barang(Request $request)
    {
        $status = $request->get('status', 'All'); // Default 'All'
        $query = BarangView::query();

        if ($status && $status !== 'All') {
            $query->where('status', $status);
        }

        $barang = $query->get();
        return view('master.barang', compact('barang', 'status'));
    }

    // 2. Filter Master Satuan
    public function satuan(Request $request)
    {
        $status = $request->get('status', 'All');
        $query = SatuanView::query();

        if ($status && $status !== 'All') {
            $query->where('status', $status);
        }

        $satuan = $query->get();
        return view('master.satuan', compact('satuan', 'status'));
    }

    // 3. Filter Master Vendor
    public function vendor(Request $request)
    {
        $Status = $request->get('Status', 'All');
        $query = VendorView::query();

        if ($Status && $Status !== 'All') {
            $query->where('Status', $Status);
        }

        $vendor = $query->get();
        return view('master.vendor', compact('vendor', 'Status'));
    }

    public function storeVendor(Request $request)
    {
        $request->validate([
            'nama_vendor' => 'required|string|max:255',
            'badan_hukum' => 'required|in:P,C,U', // P = PT, C = CV, U = UD
        ]);

        Vendor::create([
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

        $vendor = Vendor::findOrFail($id);
        $vendor->update([
            'nama_vendor' => $request->nama_vendor,
            'badan_hukum' => $request->badan_hukum,
            'status'      => $request->status,
        ]);

        return redirect()->back()->with('success', 'Data vendor berhasil diperbarui!');
    }

    // Hapus Vendor
    public function destroyVendor($id)
    {
        Vendor::destroy($id);
        return redirect()->back()->with('success', 'Vendor berhasil dihapus!');
    }

    // 4. Filter Master Margin
    public function margin(Request $request)
    {
        $status = $request->get('status', 'All');
        $query = MarginPenjualanView::query();

        if ($status && $status !== 'All') {
            $query->where('status', $status);
        }

        $margin = $query->get();
        return view('master.margin', compact('margin', 'status'));
    }

    public function user()
    {
        $user = UserView::all();
        return view('master.user', compact('user'));
    }

    public function role()
    {
        $role = RoleView::all();
        return view('master.role', compact('role'));
    }

    public function gabungan()
    {
        $barang = BarangView::all();
        $satuan = SatuanView::all();
        $vendor = VendorView::all();
        $user   = UserView::all();
        $role   = RoleView::all();
        $margin = MarginPenjualanView::all();

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
