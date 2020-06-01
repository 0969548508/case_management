<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Encryptable;
use App\Traits\HasUuid;

class Offices extends Model
{
    use HasUuid, Encryptable;
    protected $table = "offices";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'address', 'country', 'state', 'city', 'postal_code', 'phone_number', 'fax_number'
    ];

    public $incrementing = false;

    //relation
    public function users()
    {
        return $this->belongsToMany('App\User', 'users_belong_offices', 'office_id', 'user_id');
    }
}
