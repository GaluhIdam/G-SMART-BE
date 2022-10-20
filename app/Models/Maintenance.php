<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    protected $table = 'maintenances';
    protected $fillable = [
        'name',
        'description',
    ];

    public function sales()
    {
        return $this->hasMany(Sales::class);
    }

    public function tmb()
    {
        return $this->hasMany(TMB::class);
    }
}
