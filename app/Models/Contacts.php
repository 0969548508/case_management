<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;
use App\Traits\Encryptable;

class Contacts extends Model
{
    use HasUuid, Encryptable;

    protected $table = 'contacts_list';
    /**
     * @var array
     */
    // protected $encrypts = [
    //     'name', 'job_title', 'email', 'phone', 'mobile', 'fax', 'note'
    // ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id', 'location_id', 'name', 'job_title', 'email', 'phone', 'mobile', 'fax', 'note'
    ];

    /**
     * Set auto-increment to false.
     *
     * @var bool
     */
    public $incrementing = false;
}
