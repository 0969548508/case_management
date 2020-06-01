<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Encryptable;
use App\Traits\HasUuid;

class Accreditations extends Model
{
    use HasUuid, Encryptable;
    protected $table = "accreditations";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $encrypts = [
    //     'qualification'
    // ];

    protected $fillable = [
        'qualification', 'date_acquired', 'file', 'user_id'
    ];

    public $incrementing = false;
}
