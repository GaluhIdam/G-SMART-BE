<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    protected $table = 'regions';
    protected $fillable = [
        'name',
    ];
    public function country_id()
    {
        return $this->hasMany(Countries::class, 'region_id');
    }
}
