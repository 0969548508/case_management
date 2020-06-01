<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;
use App\Traits\Encryptable;

class MatterAccountInstructingClientLocation extends Model
{
    use HasUuid, Encryptable;

    protected $table = 'account_instructing_client_location';
    /**
     * @var array
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'matter_id', 'client_id', 'location_name', 'abn', 'address_1', 'address_2', 'country', 'state', 'city', 'postcode', 'primary_phone', 'secondary_phone', 'fax', 'is_account'
    ];

    /**
     * Set auto-increment to false.
     *
     * @var bool
     */
    public $incrementing = false;
}
