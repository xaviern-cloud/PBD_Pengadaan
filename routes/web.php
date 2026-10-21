<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenjualanController;


Route::get('/', function () {
    return view('dashboard');
});

Route::get('/penjualan', [PenjualanController::class, 'index']);
Route::post('/penjualan', [PenjualanController::class, 'store']);

use App\Http\Controllers\MasterController;

Route::prefix('master')->name('master.')->group(function () {
    Route::get('/barang', [MasterController::class, 'barang'])->name('barang');
    Route::get('/vendor', [MasterController::class, 'vendor'])->name('vendor');
    Route::get('/user', [MasterController::class, 'user'])->name('user');
    Route::get('/margin', [MasterController::class, 'margin'])->name('margin');
});
