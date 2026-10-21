<?php

namespace App\Http\Controllers;

use App\Models\BarangView;
use App\Models\UserView;
use App\Models\VendorView;
use App\Models\MarginPenjualanView;
use Illuminate\Http\Request;

class MasterController extends Controller
{
    // Tampilan Data Barang
    public function barang()
    {
        $barang = BarangView::all();
        return view('master.barang', compact('barang'));
    }

    // Tampilan Data Vendor
    public function vendor()
    {
        $vendor = VendorView::all();
        return view('master.vendor', compact('vendor'));
    }

    // Tampilan Data User
    public function user()
    {
        $user = UserView::all();
        return view('master.user', compact('user'));
    }

    // Tampilan Data Margin Penjualan
    public function margin()
    {
        $margin = MarginPenjualanView::all();
        return view('master.margin', compact('margin'));
    }
}
