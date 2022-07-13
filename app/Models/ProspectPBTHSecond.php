<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProspectPBTHSecond extends Model
{
    use HasFactory;
    protected $table = 'prospect_pbth_seconds';

    protected $fillable = [
        'month',
        'rate',
        'flight_hour',
    ];
}
