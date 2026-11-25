<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengadaanController;
use App\Http\Controllers\PenerimaanController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\LaporanController;

// Auth
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Pengadaan (PO)
Route::prefix('pengadaan')->name('pengadaan.')->group(function () {
    Route::get('/', [PengadaanController::class, 'index'])->name('index');
    Route::post('/cart/add', [PengadaanController::class, 'addToCart'])->name('cart.add');
    Route::delete('/cart/{id_barang}', [PengadaanController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/store', [PengadaanController::class, 'store'])->name('store');
    Route::get('/nota/{id}', [PengadaanController::class, 'nota'])->name('nota');
});

// Penerimaan Barang
Route::prefix('penerimaan')->name('penerimaan.')->group(function () {
    Route::get('/', [PenerimaanController::class, 'index'])->name('index');
    Route::post('/store', [PenerimaanController::class, 'store'])->name('store');
    Route::get('/nota/{id}', [PenerimaanController::class, 'nota'])->name('nota');
});

// Penjualan POS Kasir
Route::prefix('penjualan')->name('penjualan.')->group(function () {
    Route::get('/', [PenjualanController::class, 'index'])->name('index');
    Route::post('/cart/add', [PenjualanController::class, 'addToCart'])->name('cart.add');
    Route::delete('/cart/{id_barang}', [PenjualanController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/cart/clear', [PenjualanController::class, 'clearCart'])->name('cart.clear');
    Route::post('/checkout', [PenjualanController::class, 'checkout'])->name('checkout');
    Route::get('/nota/{id}', [PenjualanController::class, 'nota'])->name('nota');
});

// Laporan Kartu Stok
Route::get('/kartu-stok', [LaporanController::class, 'kartuStok'])->name('kartu-stok.index');
