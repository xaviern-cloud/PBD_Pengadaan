<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarginPenjualan extends Model
{
    protected $table = 'margin_penjualan'; // Menentukan nama tabel yang benar
    protected $primaryKey = 'id_margin_penjualan';
    public $timestamps = false;

    protected $fillable = [
        'created_at',
        'persen',
        'STATUS',
        'updated_at',
        'id_user',
    ];
}
