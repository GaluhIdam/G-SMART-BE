<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AMS extends Model
{
    use HasFactory;

    protected $table = 'ams';
    protected $fillable = [
        'initial',
        'user_id',
    ];
}