<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordPolicies extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pass_length', 'special_character', 'capital_letter', 'number', 'pass_period', 'password_history'
    ];
}
