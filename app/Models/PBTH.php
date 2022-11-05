<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pbth extends Model
{
    use HasFactory;

    protected $table = 'pbth';
    protected $guarded = ['id'];

    protected $aoppends = [
        'registration',
    ];

    public function getRegistrationAttribute()
    {
        $registration = $this->prospectPbth->first() ? $this->prospectPbth->first()->acType->name : '-';
        return $registration;
    }

    public function prospectPbth()
    {
        return $this->hasMany(ProspectPBTH::class);
    }
}
