<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;
use App\Traits\Encryptable;

class MatterAccountInstructingClient extends Model
{
    use HasUuid, Encryptable;

    protected $table = 'account_instructing_client';

    protected $fillable = ['matter_id', 'name', 'abn', 'billing_number', 'is_account'];

    public $incrementing = false;
}
