<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    const ROLE_ADMIN = 1;
    const ROLE_TPC = 2;
    const ROLE_TPR = 3;
    const ROLE_CBO = 4;
    const ROLE_AMS = 5;
    // const ROLE_TP = 6;
    // const ROLE_TD = 7;

    const ROLES = [
        self::ROLE_ADMIN => 'Administrator',
        self::ROLE_TPC => 'TPC',
        self::ROLE_TPR => 'TPR',
        self::ROLE_CBO => 'CBO',
        self::ROLE_AMS => 'AMS',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'role_id',
        'email',
        'unit',
        'nopeg',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

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
