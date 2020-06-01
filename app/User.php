<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\Encryptable;
use App\Traits\HasUuid;
use App\Traits\FullTextSearch;
use Spatie\Permission\Traits\HasRoles;
use OwenIt\Auditing\Contracts\Auditable;

class User extends Authenticatable implements Auditable
{
    use Notifiable, Encryptable, HasRoles, HasUuid, FullTextSearch;
    use \OwenIt\Auditing\Auditable;

    protected $guard_name = 'web';

    /**
     * @var array
     */
    // protected $encrypts = [
    //     'name', 'email', 'family_name', 'middle_name', 'phone_number', 'location_id', 'role_id',
    // ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'google2fa_secret', 'family_name', 'middle_name', 'phone_number', 'location_id', 'role_id', 'status', 'date_of_birth', 'password_expiry', 'in_trash', 'image', 'status'
    ];

    /**
     * The columns of the full text index
     */
    protected $searchable = [
        'name', 'family_name', 'middle_name', 'email',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'google2fa_secret',
    ];

    /**
     * Set auto-increment to false.
     *
     * @var bool
     */
    public $incrementing = false;

    public function generateTags(): array
    {
        return ['user'];
    }

    //relation
    public function ips()
    {
        return $this->belongsToMany('App\Models\Ips', 'users_ips', 'user_id', 'ip_id');
    }

    public function emails()
    {
        return $this->hasMany('App\Models\Emails');
    }

    public function phones()
    {
        return $this->hasMany('App\Models\Phones');
    }

    public function licenses()
    {
        return $this->hasMany('App\Models\Licenses');
    }

    public function addresses()
    {
        return $this->hasMany('App\Models\Addresses');
    }

    public function passwordHistories()
    {
        return $this->hasMany('App\Models\PasswordHistory');
    }

    public function accreditations()
    {
        return $this->hasMany('App\Models\Accreditations');
    }

    public function equipments()
    {
        return $this->hasMany('App\Models\Equipments');
    }

    public function offices()
    {
        return $this->belongsToMany('App\Models\Offices', 'users_belong_offices', 'user_id', 'office_id');
    }

    public function types()
    {
        return $this->belongsToMany('App\Models\SpecificMatters', 'users_belong_types', 'user_id', 'type_id');
    }

    /**
     * Ecrypt the user's google_2fa secret.
     *
     * @param  string  $value
     * @return string
     */
    public function setGoogle2faSecretAttribute($value)
    {
        $this->attributes['google2fa_secret'] = encrypt($value);
    }

    /**
     * Decrypt the user's google_2fa secret.
     *
     * @param  string  $value
     * @return string
     */
    public function getGoogle2faSecretAttribute($value)
    {
        if (is_null($value))
        {
            return false;
        }
        return decrypt($value);
    }
}
