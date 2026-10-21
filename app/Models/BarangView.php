<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangView extends Model
{
    protected $table = 'v_barang';
    protected $primaryKey = 'id_barang';
    public $timestamps = false;
}
