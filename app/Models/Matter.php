<?php

namespace App\Models;

use App\Traits\Statable;
use App\Traits\HasUuid;
use App\Traits\FullTextSearch;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Matter extends Model implements Auditable
{
    use Statable, HasUuid, FullTextSearch;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'cases';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'case_number', 'user_id', 'client_id', 'location_id', 'type_id', 'office_id', 'last_state',
    ];

    /**
     * The columns of the full text index
     */
    protected $searchable = [
        'case_number', 'last_state'
    ];

    /**
     * Set auto-increment to false.
     *
     * @var bool
     */
    public $incrementing = false;

    public function generateTags(): array
    {
        return ['matter'];
    }

    const HISTORY_MODEL = [
        'name' => 'App\Models\MatterState', // the related model to store the history
        'foreign_key' => 'id'
    ];
    const PRIMARY_KEY = 'id';
    const SM_CONFIG = 'case'; // the SM graph to use

    // other relations and methods of the model

    public function office()
    {
        return $this->belongsTo('App\Models\Offices', 'office_id', 'id');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\SpecificMatters', 'type_id', 'id');
    }

    public function clients()
    {
        return $this->belongsTo('App\Models\Clients', 'client_id', 'id');
    }

    public function locations()
    {
        return $this->belongsTo('App\Models\Locations', 'location_id', 'id');
    }

    public function types()
    {
        return $this->belongsTo('App\Models\SpecificMatters', 'type_id', 'id');
    }

    public function offices()
    {
        return $this->belongsTo('App\Models\Offices', 'office_id', 'id');
    }

    public function users()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function mattersusers()
    {
        return $this->belongsTo('App\Models\MattersUsers', 'id', 'case_id');
    }
}
