<?php

namespace App\Models;

use App\Models\User;
use App\Models\Customer;
use App\Models\ProspectType;
use App\Models\TransactionType;
use App\Models\StrategicInitiatives;
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
        return $this->belongsTo(TransactionType::class, 'transaction_type_id');
    }

    public function prospect_type_id()
    {
        return $this->belongsTo(ProspectType::class, 'prospect_type_id');
    }

    public function strategic_initiative_id()
    {
        return $this->belongsTo(StrategicInitiatives::class, 'strategic_initiative_id');
    }

    public function pm_id()
    {
        return $this->belongsTo(User::class, 'pm_id');
    }

    public function customer_id()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
