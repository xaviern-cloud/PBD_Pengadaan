<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Barang;
use App\Models\MarginPenjualan;
use App\Models\DetailPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // 1. Tambahkan Import Auth

class PenjualanController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_margin_penjualan' => 'required|integer|exists:margin_penjualan,id_margin_penjualan',
            'subtotal_nilai'      => 'required|numeric',
            'ppn'                 => 'nullable|integer',
            'total_nilai'         => 'required|numeric',
            'items'               => 'required|array|min:1',
            'items.*.id_barang'   => 'required|integer|exists:barang,id_barang',
            'items.*.harga_satuan'=> 'required|numeric',
            'items.*.jumlah'      => 'required|integer|min:1',
            'items.*.subtotal'    => 'required|numeric',
        ]);

        return DB::transaction(function () use ($validated) {
            // 1. Simpan Header Penjualan
            $penjualan = Penjualan::create([
                'CREATED_at'          => now(),
                'subtotal_nilai'      => $validated['subtotal_nilai'],
                'ppn'                 => $validated['ppn'] ?? 0,
                'total_nilai'         => $validated['total_nilai'],
                'id_user'             => Auth::id() ?? 1, // 2. Gunakan Auth::id()
                'id_margin_penjualan' => $validated['id_margin_penjualan'],
            ]);

            // 2. Simpan Detail Barang yang Dibeli
            foreach ($validated['items'] as $item) {
                DetailPenjualan::create([
                    'id_penjualan' => $penjualan->id_penjualan,
                    'id_barang'    => $item['id_barang'],
                    'harga_satuan' => $item['harga_satuan'],
                    'jumlah'       => $item['jumlah'],
                    'subtotal'     => $item['subtotal'],
                ]);
            }

            return response()->json([
                'message' => 'Transaksi berhasil disimpan',
                'data'    => $penjualan->load('details.barang')
            ], 201);
        });
    }

    public function index()
    {
        $barangs = Barang::with('satuan')->where('STATUS', 'A')->get();
        $margins = MarginPenjualan::where('STATUS', 'A')->get();

        return view('penjualan.index', compact('barangs', 'margins'));
    }
}
