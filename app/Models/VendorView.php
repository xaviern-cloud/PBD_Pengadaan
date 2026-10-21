<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorView extends Model
{
    protected $table = 'v_vendor';
    protected $primaryKey = 'id_vendor';
    public $timestamps = false;
}
