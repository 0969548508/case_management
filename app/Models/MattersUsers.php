<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MattersUsers extends Model
{
    protected $table = 'cases_users';

    public function userofmatter()
    {
        return $this->hasMany('App\User', 'id', 'user_id');
    }

    public function matter()
    {
        return $this->hasOne('App\Models\Matter', 'id', 'case_id');
    }
}
