<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use LdapRecord\Laravel\Auth\LdapAuthenticatable;
use LdapRecord\Laravel\Auth\AuthenticatesWithLdap;

class User extends Authenticatable implements LdapAuthenticatable
{
    use HasApiTokens, Notifiable, HasRoles, AuthenticatesWithLdap;

    const ROLE_ADMIN = 1;
    const ROLE_TPC = 2;
    const ROLE_TPR = 3;
    const ROLE_CBO = 4;
    const ROLE_AMS = 5;
    const ROLE_INIT = 6;

    const ROLES = [
        self::ROLE_ADMIN => 'Administrator',
        self::ROLE_TPC => 'TPC',
        self::ROLE_TPR => 'TPR',
        self::ROLE_CBO => 'CBO',
        self::ROLE_AMS => 'AMS',
        self::ROLE_INIT => 'Initial',
    ];

    protected $fillable = [
        'name',
        'username',
        'role_id',
        'email',
        'unit',
        'nopeg',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $guard_name = 'web';

    public function ams()
    {
        return $this->hasOne(AMS::class);
    }

    public function prospects()
    {
        return $this->hasMany(Prospect::class);
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
