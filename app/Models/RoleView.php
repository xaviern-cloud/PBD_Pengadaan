<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleView extends Model
{
    protected $table = 'v_role';
    protected $primaryKey = 'id_role';
    public $timestamps = false;
}
