<?php

namespace App\Http\Controllers;

use App\Models\BarangView;
use App\Models\SatuanView;
use App\Models\VendorView;
use App\Models\UserView;
use App\Models\RoleView;
use App\Models\MarginPenjualanView;

class MasterController extends Controller
{
    public function barang()
    {
        $barang = BarangView::all();
        return view('master.barang', compact('barang'));
    }

    public function satuan()
    {
        $satuan = SatuanView::all();
        return view('master.satuan', compact('satuan'));
    }

    public function vendor()
    {
        $vendor = VendorView::all();
        return view('master.vendor', compact('vendor'));
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

    public function margin()
    {
        $margin = MarginPenjualanView::all();
        return view('master.margin', compact('margin'));
    }
}
