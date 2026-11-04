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
    // 1. Barang
    Route::get('/barang', [MasterController::class, 'barang'])->name('barang');
    Route::post('/barang', [MasterController::class, 'storeBarang'])->name('barang.store');
    Route::put('/barang/{id}', [MasterController::class, 'updateBarang'])->name('barang.update');
    Route::delete('/barang/{id}', [MasterController::class, 'destroyBarang'])->name('barang.destroy');

    // 2. Satuan
    Route::get('/satuan', [MasterController::class, 'satuan'])->name('satuan');
    Route::post('/satuan', [MasterController::class, 'storeSatuan'])->name('satuan.store');
    Route::put('/satuan/{id}', [MasterController::class, 'updateSatuan'])->name('satuan.update');
    Route::delete('/satuan/{id}', [MasterController::class, 'destroySatuan'])->name('satuan.destroy');

    // 3. Vendor
    Route::get('/vendor', [MasterController::class, 'vendor'])->name('vendor');
    Route::post('/vendor', [MasterController::class, 'storeVendor'])->name('vendor.store');
    Route::put('/vendor/{id}', [MasterController::class, 'updateVendor'])->name('vendor.update');
    Route::delete('/vendor/{id}', [MasterController::class, 'destroyVendor'])->name('vendor.destroy');

    // 4. Margin Penjualan
    Route::get('/margin', [MasterController::class, 'margin'])->name('margin');
    Route::post('/margin', [MasterController::class, 'storeMargin'])->name('margin.store');
    Route::put('/margin/{id}', [MasterController::class, 'updateMargin'])->name('margin.update');
    Route::delete('/margin/{id}', [MasterController::class, 'destroyMargin'])->name('margin.destroy');

    // 5. User
    Route::get('/user', [MasterController::class, 'user'])->name('user');
    Route::post('/user', [MasterController::class, 'storeUser'])->name('user.store');
    Route::put('/user/{id}', [MasterController::class, 'updateUser'])->name('user.update');
    Route::delete('/user/{id}', [MasterController::class, 'destroyUser'])->name('user.destroy');

    // 6. Role
    Route::get('/role', [MasterController::class, 'role'])->name('role');
    Route::post('/role', [MasterController::class, 'storeRole'])->name('role.store');
    Route::put('/role/{id}', [MasterController::class, 'updateRole'])->name('role.update');
    Route::delete('/role/{id}', [MasterController::class, 'destroyRole'])->name('role.destroy');

    // 7. Gabungan
    Route::get('/gabungan', [MasterController::class, 'gabungan'])->name('gabungan');
});
