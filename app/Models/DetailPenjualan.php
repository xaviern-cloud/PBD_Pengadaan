<?php

    namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    protected $table = 'detail_penjualan';
    protected $primaryKey = 'id_detail_penjualan';
    public $timestamps = false;

    protected $fillable = ['harga_satuan', 'jumlah', 'subtotal', 'id_penjualan', 'id_barang'];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }
}
