<?php

namespace App\Models;

use App\Models\TransactionTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prospect extends Model
{
    use HasFactory;
    protected $fillable = [
        'year',
        'transaction_type_id',
        'prospect_type_id',
        'strategic_initiative_id',
        'pm_id',
        'customer_id',
    ];

    public function transaction_type_id()
    {
        return $this->hasMany(TransactionTypes::class, 'id');
    }

    public function prospect_type_id()
    {
        return $this->hasMany(ProspectTypes::class, 'id');
    }

    public function strategic_initiative_id()
    {
        return $this->hasMany(StrategicInitiatives::class, 'id');
    }

    public function pm_id()
    {
        return $this->hasMany(User::class, 'id');
    }

    public function customer_id()
    {
        return $this->hasMany(Customer::class, 'id');
    }
}
