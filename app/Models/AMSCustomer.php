<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AMSCustomer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'area_id',
        'ams_id',
    ];
}
