<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserIp extends Model
{
    protected $table = "users_ips";
    public $timestamps = false;
}
