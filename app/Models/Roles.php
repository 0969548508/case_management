<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as BaseRole;
use OwenIt\Auditing\Contracts\Auditable;

class Roles extends BaseRole implements Auditable
{
	use \OwenIt\Auditing\Auditable;

    protected $table = "roles";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'guard_name', 'description',
    ];

    public function generateTags(): array
    {
        return ['role'];
    }
}
