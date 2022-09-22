<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AircraftType extends Model
{
    use HasFactory;

    protected $table = 'ac_type_id';
    protected $fillable = [
        'name',
    ];

    public function TMB()
    {
        return $this->hasMany(TMB::class);
    }

    public function sales()
    {
        return $this->hasMany(Sales::class);
    }

    public function prospectPBTH()
    {
        return $this->hasMany(ProspectPBTH::class);
    }
}
