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
        'ams_customer_id',
    ];

    protected $appends = [
        'market_share',
    ];

    public function getMarketShareAttribute()
    {
        return $this->prospectTmb->sum('tmb.market_share');
    }

    public function transactionType()
    {
        return $this->belongsTo(TransactionType::class, 'transaction_type_id');
    }

    public function prospectType()
    {
        return $this->belongsTo(ProspectType::class, 'prospect_type_id');
    }

    public function strategicInitiative()
    {
        return $this->belongsTo(StrategicInitiatives::class, 'strategic_initiative_id');
    }

    public function pm()
    {
        return $this->belongsTo(User::class, 'pm_id');
    }

    public function sales()
    {
        return $this->hasMany(Sales::class);
    }
    
    public function amsCustomer()
    {
        return $this->belongsTo(AMSCustomer::class, 'ams_customer_id');
    }

    public function prospectTmb()
    {
        return $this->hasMany(ProspectTMB::class);
    }

    public function prospectPbth()
    {
        return $this->hasMany(ProspectPBTH::class);
    }
}
