<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;
use App\Traits\Encryptable;

class MatterAccountInstructingContact extends Model
{
    use HasUuid, Encryptable;

    protected $table = 'account_instructing_contact';
    /**
     * @var array
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'matter_id', 'client_id', 'location_id', 'name', 'job_title', 'email', 'phone', 'mobile', 'fax', 'note', 'is_account'
    ];

    /**
     * Set auto-increment to false.
     *
     * @var bool
     */
    public $incrementing = false;
}
