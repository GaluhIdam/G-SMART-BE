<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactPersoon extends Model
{
    use HasFactory;

    protected $table = 'contact_persons';
    protected $guarded = ['id'];

    public function customer()
    {
        return $this->belongsTi(Customer::class, 'customer_id');
    }
}
