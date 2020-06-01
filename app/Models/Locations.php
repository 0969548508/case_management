<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Traits\HasUuid;
use App\Traits\Encryptable;
use Log;

class Locations extends Model implements Auditable
{
    use HasUuid, Encryptable;
    use \OwenIt\Auditing\Auditable;

    /**
     * @var array
     */
    // protected $encrypts = [
    //     'name', 'abn', 'primary_phone', 'secondary_phone', 'fax', 'description',
    // ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id', 'name', 'abn', 'is_primary'
    ];

    /**
     * Set auto-increment to false.
     *
     * @var bool
     */
    public $incrementing = false;

    public function generateTags(): array
    {
        return ['location'];
    }

    public function priceLocations()
    {
        return $this->hasMany('App\Models\PriceLocations', 'location_id', 'id');
    }
}
