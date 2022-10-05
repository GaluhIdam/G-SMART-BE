<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Line extends Model
{
    use HasFactory;

    protected $table = 'lines';
    protected $guarded = [];

    public function hangar()
    {
        return $this->belongsTo(Hangar::class, 'hangar_id')
    }

    public function sales()
    {
        return $this->hasMany(Sales::class);
    }
}
