<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $table = 'penjualan';
    protected $primaryKey = 'id_penjualan';
    public $timestamps = false; // Karena menggunakan nama kolom CREATED_at custom

    protected $fillable = [
        'CREATED_at', 'subtotal_nilai', 'ppn', 'total_nilai',
        'id_user', 'id_margin_penjualan'
    ];

    public function details()
    {
        return $this->hasMany(DetailPenjualan::class, 'id_penjualan', 'id_penjualan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function marginPenjualan()
    {
        return $this->belongsTo(MarginPenjualan::class, 'id_margin_penjualan', 'id_margin_penjualan');
    }
}
