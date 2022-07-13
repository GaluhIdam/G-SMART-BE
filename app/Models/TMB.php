<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TMB extends Model
{
    use HasFactory;

    protected $table = 'tmb';

    protected $fillable = [
        'product_id',
        'ac_type_id',
        'component_id',
        'engine_id',
        'apu_id',
        'market_share',
        'remarks',
        'maintenance_id',
    ];
}
