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

    public function latestCP()
    {
        return $this->hasOne(ContactPerson::class)->latest('updated_at');
    }

    public function country()
    {
        return $this->belongsTo(Countries::class, 'country_id');
    }

    public function amsCustomers()
    {
        return $this->hasMany(AMSCustomer::class);
    }

    public function contactPersons()
    {
        return $this->hasMany(ContactPerson::class);
    }
}
