<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;
use App\Traits\Encryptable;
use Log;

class PriceLocations extends Model
{
    use HasUuid, Encryptable;

    /**
     * @var array
     */
    // protected $encrypts = [
    //     'name', 'description', 'default_price', 'default_tax_rate', 'custom_price', 'custom_tax_rate',
    // ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'location_id', 'name', 'description', 'default_price', 'default_tax_rate', 'custom_price', 'custom_tax_rate', 'rate_id', 'is_updated',
    ];

    /**
     * Set auto-increment to false.
     *
     * @var bool
     */
    public $incrementing = false;
}
