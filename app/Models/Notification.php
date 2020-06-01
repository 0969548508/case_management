<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;
use Auth;
use Log;

class Notification extends Model
{
    use HasUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'notifiable_type', 'notifiable_id', 'data', 'read_at',
    ];

    /**
     * Set auto-increment to false.
     *
     * @var bool
     */
    public $incrementing = false;
}
