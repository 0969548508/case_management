<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Encryptable;
use App\Traits\HasUuid;
use App\Models\UsersBelongMatters;
use App\User;

class SpecificMatters extends Model
{
    use HasUuid, Encryptable;
    protected $table = "types";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'parent_id'
    ];

    public $incrementing = false;

    public function children() {
        return $this->hasMany(__CLASS__, 'parent_id', 'id');
    }

    public function parent() {
        return $this->hasMany(__CLASS__, 'id', 'parent_id');
    }

    public function users()
    {
        return $this->belongsToMany('App\User', 'users_belong_types', 'type_id', 'user_id');
    }
}
