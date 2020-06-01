<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;
use App\Traits\Encryptable;

class Libraries extends Model
{
    use HasUuid, Encryptable;

    protected $table = 'libraries';
    /**
     * @var array
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'license_id', 'image'
    ];

    /**
     * Set auto-increment to false.
     *
     * @var bool
     */
    public $incrementing = false;
}
