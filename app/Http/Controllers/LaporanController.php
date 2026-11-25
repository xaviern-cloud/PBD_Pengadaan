<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function kartuStok(Request $request)
    {
        $jenis = $request->query('jenis', 'All');
        $pdo = DB::getPdo();

        if ($jenis === 'M') {
            $kartuStok = $pdo->query("SELECT * FROM vw_kartu_stok WHERE jenis_transaksi LIKE '%Masuk%'")->fetchAll(\PDO::FETCH_OBJ);
        } elseif ($jenis === 'K') {
            $kartuStok = $pdo->query("SELECT * FROM vw_kartu_stok WHERE jenis_transaksi LIKE '%Keluar%'")->fetchAll(\PDO::FETCH_OBJ);
        } else {
            $kartuStok = $pdo->query("SELECT * FROM vw_kartu_stok")->fetchAll(\PDO::FETCH_OBJ);
        }

        return view('laporan.kartu_stok', compact('kartuStok', 'jenis'));
    }
}
