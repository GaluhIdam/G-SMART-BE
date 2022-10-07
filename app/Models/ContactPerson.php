<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactPerson extends Model
{
    use HasFactory;

    protected $table = 'contact_persons';
    protected $guarded = ['id'];

    public function scopeActive($query)
    {
        $query->where('status', 1);
    }

    public function scopeByCustomer($query, $customer)
    {
        $query->when($customer, function ($query) use ($customer) {
            $query->where('customer_id', $customer);
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
