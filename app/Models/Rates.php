<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Traits\HasUuid;
use App\Traits\Encryptable;
use Log;

class Rates extends Model implements Auditable
{
    use HasUuid, Encryptable;
    use \OwenIt\Auditing\Auditable;

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
        'name', 'description', 'default_price', 'default_tax_rate', 'custom_price', 'custom_tax_rate',
    ];

    /**
     * Set auto-increment to false.
     *
     * @var bool
     */
    public $incrementing = false;

    public function generateTags(): array
    {
        return ['rate'];
    }
}
