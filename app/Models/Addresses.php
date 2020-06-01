<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Encryptable;
use App\Traits\HasUuid;

class Addresses extends Model
{
    use HasUuid, Encryptable;
    protected $table = "addresses";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $encrypts = [
    //     'type_name', 'address', 'country', 'state', 'city', 'postal_code'
    // ];

    protected $fillable = [
        'type_name', 'address', 'country', 'state', 'city', 'postal_code', 'is_primary'
    ];

    public $incrementing = false;
}
