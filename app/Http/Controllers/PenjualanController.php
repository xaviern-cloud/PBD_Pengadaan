<?php

namespace App\Http\Controllers;

use App\Models\BarangView;
use App\Models\MarginPenjualanView;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    public function index()
    {
        // Mengambil data dari SQL VIEW database
        $barangs = BarangView::all();
        $margins = MarginPenjualanView::where('status', 1)->get();

        return view('penjualan.index', compact('barangs', 'margins'));
    }

    public function store(Request $request)
    {
        // Logika simpan transaksi penjualan
        return response()->json(['message' => 'Transaksi berhasil disimpan!']);
    }
}
