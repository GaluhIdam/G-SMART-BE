<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'country_id',
    ];

    protected $appends = [
        'full_path',
    ];

    public function scopeTransactionTypeGroup($query, $id, $year)
    {
        $customer = Customer::find($id);
        $ams_ids = $customer->amsCustomers->pluck('id')->toArray();
        $transaction_type = Prospect::whereIn('ams_customer_id', $ams_ids)->where('year', $year)->pluck('transaction_type_id')->toArray();
        $transactions = TransactionType::whereIn('id', $transaction_type)->pluck('name')->toArray();
        $result = array_unique($transactions);

        return implode(', ', $result);
    }
    
    public function scopeProspectTypeGroup($query, $id, $year)
    {
        $customer = Customer::find($id);
        $ams_ids = $customer->amsCustomers->pluck('id')->toArray();
        $prospect_type = Prospect::whereIn('ams_customer_id', $ams_ids)->where('year', $year)->pluck('prospect_type_id')->toArray();
        $prospects = ProspectType::whereIn('id', $prospect_type)->pluck('name')->toArray();
        $result = array_unique($prospects);

        return implode(', ', $result);
    }

    public function scopeStrategicInitiativeGroup($query, $id, $year)
    {
        $customer = Customer::find($id);
        $ams_ids = $customer->amsCustomers->pluck('id')->toArray();
        $strategic_initiative = Prospect::whereIn('ams_customer_id', $ams_ids)->where('year', $year)->pluck('strategic_initiative_id')->toArray();
        $strategics = StrategicInitiatives::whereIn('id', $strategic_initiative)->pluck('name')->toArray();
        $result = array_unique($strategics);

        return implode(', ', $result);
    }

    public function scopePmGroup($query, $id, $year)
    {
        $customer = Customer::find($id);
        $ams_ids = $customer->amsCustomers->pluck('id')->toArray();
        $project_manager = Prospect::whereIn('ams_customer_id', $ams_ids)->where('year', $year)->pluck('pm_id')->toArray();
        $pms = User::whereIn('id', $project_manager)->pluck('name')->toArray();
        $result = array_unique($pms);

        return implode(', ', $pms);
    }

    public function scopeAmsGroup($query, $id)
    {
        $customer = Customer::find($id);
        $ams_customers = $customer->amsCustomers;
        
        $initial = [];
        foreach ($ams_customers as $item) {
            $initial[] = $item->ams->initial;
        }
        
        return implode(', ', $initial);
    }
    
    public function scopeMarketShareGroup($query, $id, $year)
    {
        $customer = Customer::find($id);
        $ams_ids = $customer->amsCustomers->pluck('id')->toArray();
        $prospect = Prospect::whereIn('ams_customer_id', $ams_ids)->where('year', $year)->get();
        
        return $prospect->sum('market_share');
    }

    public function scopeSalesPlanGroup($query, $id, $year)
    {
        $customer = Customer::find($id);
        $ams_ids = $customer->amsCustomers->pluck('id')->toArray();
        $prospect = Prospect::whereIn('ams_customer_id', $ams_ids)->where('year', $year)->get();
        
        return $prospect->sum('sales_plan');
    }

    public function getFullPathAttribute()
    {
        return Storage::disk('public')->url($this->logo_path);
    }

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

    public function sales()
    {
        return $this->hasMany(Sales::class);
    }
}
