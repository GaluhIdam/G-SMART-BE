<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProspectPBTH extends Model
{
    use HasFactory;

    protected $table = 'prospect_pbth';

    protected $fillable = [
        'prospect_id',
        'pbth_id',
        'product_id',
        'ac_type_id',
    ];
}
