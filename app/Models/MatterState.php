<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatterState extends Model
{
	protected $table = 'case_states';

    protected $fillable = ['transition', 'from', 'user_id', 'case_id', 'to'];

    public function matter()
    {
        return $this->belongsTo('App\Models\Matter');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
