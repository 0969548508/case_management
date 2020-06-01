<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Encryptable;
use App\Traits\HasUuid;
use App\Traits\FullTextSearch;

class Emails extends Model
{
    use HasUuid, Encryptable, FullTextSearch;
    protected $table = "emails";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $encrypts = [
    //     'email', 'type_name',
    // ];

    protected $fillable = [
        'email', 'type_name',
    ];

    protected $searchable = [
        'email',
    ];

    public $incrementing = false;

    public function users()
    {
        return $this->belongsTo('App\User');
    }
}
