<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'country_id',
    ];

    public function country()
    {
        return $this->belongsTo(Countries::class, 'country_id');
    }

    public function amscustomer()
    {
        return $this->hasMany(AMSCustomer::class, 'customer_id');
    }
}
