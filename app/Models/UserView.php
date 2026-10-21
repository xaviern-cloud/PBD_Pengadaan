<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserView extends Model
{
    protected $table = 'v_user';
    protected $primaryKey = 'id_user';
    public $timestamps = false;
}
