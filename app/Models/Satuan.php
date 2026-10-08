<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    protected $table = 'satuan'; // Menentukan nama tabel yang benar
    protected $primaryKey = 'id_satuan';
    public $timestamps = false;

    protected $fillable = [
        'nama_satuan',
        'STATUS',
    ];
}
