<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangView;
use App\Models\SatuanView;
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
        $Status = $request->get('status', 'All');
        $query = VendorView::query();

        if ($Status && $Status !== 'All') {
            $query->where('Status', $Status);
        }

        $vendor = $query->get();
        return view('master.vendor', compact('vendor', 'Status'));
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
