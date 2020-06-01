<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Encryptable;
use App\Traits\HasUuid;
use App\Traits\FullTextSearch;

class Phones extends Model
{
	use HasUuid, Encryptable, FullTextSearch;
    protected $table = "phones";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $encrypts = [
    //     'phone_number', 'type_name',
    // ];

    protected $fillable = [
        'phone_number', 'type_name',
    ];

    protected $searchable = [
        'phone_number',
    ];

    public $incrementing = false;

    public function users()
    {
        return $this->belongsTo('App\User');
    }
}
