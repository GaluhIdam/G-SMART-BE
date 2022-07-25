<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApuId extends Model
{
    use HasFactory;
    protected $table = 'apu_ids';
    protected $fillable = [
        'name',
    ];
}

