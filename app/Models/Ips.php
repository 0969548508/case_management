<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ips extends Model
{
    //relation
    public function users()
    {
        return $this->belongsToMany('App\User', 'users_ips', 'ip_id', 'user_id');
    }
}
