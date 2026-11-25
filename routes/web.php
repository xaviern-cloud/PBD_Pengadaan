<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\PengadaanController;
use App\Http\Controllers\PenerimaanController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ReturController;

// Auth
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 1. AKSES ADMIN & MANAGER (Dashboard, Master Data [DELETE otomatis diblokir CheckRole jika Manager], Pengadaan, Penerimaan, Kartu Stok)
Route::middleware(['role:Admin,Manager'])->group(function () {
    Route::get('/dashboard', [MasterController::class, 'dashboard'])->name('dashboard');
    Route::get('/kartu-stok', [MasterController::class, 'kartuStok'])->name('kartu-stok.index');

    // Master Data
    Route::prefix('master')->name('master.')->group(function () {
        Route::get('/barang', [MasterController::class, 'barang'])->name('barang');
        Route::post('/barang', [MasterController::class, 'storeBarang'])->name('barang.store');
        Route::put('/barang/{id}', [MasterController::class, 'updateBarang'])->name('barang.update');
        Route::delete('/barang/{id}', [MasterController::class, 'destroyBarang'])->name('barang.destroy');

        Route::get('/satuan', [MasterController::class, 'satuan'])->name('satuan');
        Route::post('/satuan', [MasterController::class, 'storeSatuan'])->name('satuan.store');
        Route::put('/satuan/{id}', [MasterController::class, 'updateSatuan'])->name('satuan.update');
        Route::delete('/satuan/{id}', [MasterController::class, 'destroySatuan'])->name('satuan.destroy');

        Route::get('/vendor', [MasterController::class, 'vendor'])->name('vendor');
        Route::post('/vendor', [MasterController::class, 'storeVendor'])->name('vendor.store');
        Route::put('/vendor/{id}', [MasterController::class, 'updateVendor'])->name('vendor.update');
        Route::delete('/vendor/{id}', [MasterController::class, 'destroyVendor'])->name('vendor.destroy');

        Route::get('/user', [MasterController::class, 'user'])->name('user');
        Route::post('/user', [MasterController::class, 'storeUser'])->name('user.store');
        Route::put('/user/{id}', [MasterController::class, 'updateUser'])->name('user.update');
        Route::delete('/user/{id}', [MasterController::class, 'destroyUser'])->name('user.destroy');

        Route::get('/role', [MasterController::class, 'role'])->name('role');
        Route::post('/role', [MasterController::class, 'storeRole'])->name('role.store');
        Route::put('/role/{id}', [MasterController::class, 'updateRole'])->name('role.update');
        Route::delete('/role/{id}', [MasterController::class, 'destroyRole'])->name('role.destroy');

        Route::get('/margin', [MasterController::class, 'margin'])->name('margin');
        Route::post('/margin', [MasterController::class, 'storeMargin'])->name('margin.store');
        Route::put('/margin/{id}', [MasterController::class, 'updateMargin'])->name('margin.update');
        Route::delete('/margin/{id}', [MasterController::class, 'destroyMargin'])->name('margin.destroy');
    });

    // Pengadaan (PO)
    Route::get('/pengadaan', [PengadaanController::class, 'index'])->name('pengadaan.index');
    Route::post('/pengadaan/cart/add', [PengadaanController::class, 'addToCart'])->name('pengadaan.cart.add');
    Route::delete('/pengadaan/cart/{id}', [PengadaanController::class, 'removeFromCart'])->name('pengadaan.cart.remove');
    Route::post('/pengadaan', [PengadaanController::class, 'store'])->name('pengadaan.store');
    Route::get('/pengadaan/{id}/nota', [PengadaanController::class, 'nota'])->name('pengadaan.nota');

    // Penerimaan
    Route::get('/penerimaan', [PenerimaanController::class, 'index'])->name('penerimaan.index');
    Route::post('/penerimaan', [PenerimaanController::class, 'store'])->name('penerimaan.store');
    Route::get('/penerimaan/{id}/nota', [PenerimaanController::class, 'nota'])->name('penerimaan.nota');
});

// 2. AKSES KASIR, MANAGER, & ADMIN (Penjualan & Retur)
Route::middleware(['role:Kasir,Manager,Admin'])->group(function () {
    // Penjualan (POS)
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
    Route::post('/penjualan/cart/add', [PenjualanController::class, 'addToCart'])->name('penjualan.cart.add');
    Route::delete('/penjualan/cart/{id}', [PenjualanController::class, 'removeFromCart'])->name('penjualan.cart.remove');
    Route::post('/penjualan/cart/clear', [PenjualanController::class, 'clearCart'])->name('penjualan.cart.clear');
    Route::post('/penjualan/checkout', [PenjualanController::class, 'checkout'])->name('penjualan.checkout');
    Route::get('/penjualan/{id}/nota', [PenjualanController::class, 'nota'])->name('penjualan.nota');

    // Retur Barang
    Route::get('/retur', [ReturController::class, 'index'])->name('retur.index');
    Route::post('/retur', [ReturController::class, 'store'])->name('retur.store');
    Route::get('/retur/{id}/nota', [ReturController::class, 'nota'])->name('retur.nota');
});
