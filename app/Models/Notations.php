<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Encryptable;
use App\Traits\HasUuid;

class Notations extends Model
{
    use HasUuid, Encryptable;
    protected $table = "notations";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file', 'note'
    ];

    public $incrementing = false;
}
