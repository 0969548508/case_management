<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;
use App\Traits\Encryptable;

class Milestones extends Model
{
	use HasUuid, Encryptable;
    protected $table = "milestones";
    protected $fillable = ['case_id', 'date_type', 'date'];
    public $incrementing = false;
}
