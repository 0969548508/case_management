<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Traits\HasUuid;
use App\Traits\Encryptable;
use App\Traits\FullTextSearch;
use Log;

class Clients extends Model implements Auditable
{
    use HasUuid, Encryptable, FullTextSearch;
    use \OwenIt\Auditing\Auditable;

    /**
     * @var array
     */
    // protected $encrypts = [
    //     'name', 'abn',
    // ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'abn', 'image', 'user_id',
    ];

    /**
     * The columns of the full text index
     */
    protected $searchable = [
        'name', 'abn',
    ];

    /**
     * Set auto-increment to false.
     *
     * @var bool
     */
    public $incrementing = false;

    public function generateTags(): array
    {
        return ['client'];
    }

    //relation
    public function locations()
    {
        return $this->hasMany('App\Models\Locations', 'client_id', 'id');
    }

    public function contacts()
    {
        return $this->hasMany('App\Models\Contacts', 'client_id', 'id');
    }

    public function priceClients()
    {
        return $this->hasMany('App\Models\PriceClients', 'client_id', 'id');
    }
}
