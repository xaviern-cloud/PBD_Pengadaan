<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\MasterController;


Route::get('/', function () {
    return view('dashboard');
});

Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
Route::post('/penjualan', [PenjualanController::class, 'store'])->name('penjualan.store');

Route::prefix('master')->name('master.')->group(function () {
    Route::get('/barang', [MasterController::class, 'barang'])->name('barang');
    Route::get('/satuan', [MasterController::class, 'satuan'])->name('satuan');
    Route::get('/vendor', [MasterController::class, 'vendor'])->name('vendor');
    Route::get('/user', [MasterController::class, 'user'])->name('user');
    Route::get('/role', [MasterController::class, 'role'])->name('role');
    Route::get('/margin', [MasterController::class, 'margin'])->name('margin');
    Route::get('/gabungan', [MasterController::class, 'gabungan'])->name('gabungan');
});
