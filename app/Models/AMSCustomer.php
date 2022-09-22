<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AMSCustomer extends Model
{
    use HasFactory;
    protected $table = 'ams_customers';
    protected $fillable = [
        'customer_id',
        'area_id',
        'ams_id',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function ams()
    {
        return $this->belongsTo(AMS::class, 'ams_id');
    }

    public function prospects()
    {
        return $this->hasMany(Prospect::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
