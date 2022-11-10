<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Sales;
use App\Models\AMSCustomer;
use App\Models\ProspectTMB;
use App\Models\ProspectPBTH;
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
        'registration',
        'transaction',
        'type',
        'strategic_init',
        'project_manager',
        'customer',
        'ams',
        'market_share',
        'sales_plan',
    ];

    public function scopeSearch($query, $search)
    {
        $query->when($search, function ($query) use ($search) {
            $query->where('year', 'LIKE', "%$search%")
            ->orWhereRelation('transactionType', 'name', 'LIKE', "%$search%")
            ->orWhereRelation('prospectType', 'name', 'LIKE', "%$search%")
            ->orWhereRelation('strategicInitiative', 'name', 'LIKE', "%$search%")
            ->orWhereRelation('pm', 'name', 'LIKE', "%$search%")
            ->orWhereRelation('prospectType', 'name', 'LIKE', "%$search%")
            ->orWhereHas('amsCustomer', function ($query) use ($search) {
                $query->whereRelation('customer', 'name', 'LIKE', "%$search%")
                    ->orWhereRelation('customer', 'code', 'LIKE', "%$search%")
                    ->orWhereRelation('ams', 'initial', 'LIKE', "%$search%");
            });
        });
    }

    public function scopeFilter($query, $filter)
    {
        $query->when($filter, function ($query) use ($filter) {
            $query->where('year', $filter);
        });
    }

    public function scopeUser($query, $user)
    {
        $query->when($user->hasRole('AMS'), function ($query) use ($user) {   
            $query->whereHas('amsCustomer', function ($query) use ($user) {
                $query->where('ams_id', $user->ams->id);
            });
        });
    }

    public function getAmsAttribute()
    {
        return $this->amsCustomer->ams->initial;
    }

    public function getCustomerAttribute()
    {
        return $this->amsCustomer->customer->only('id', 'code', 'name');
    }

    public function getTransactionAttribute()
    {
        return $this->transactionType->name;
    }

    public function getTypeAttribute()
    {
        return $this->prospectType->name;
    }

    public function getStrategicInitAttribute()
    {
        $strategic_init = $this->strategicInitiative;
        return $strategic_init ? $strategic_init->name : null;
    }

    public function getProjectManagerAttribute()
    {
        $project_manager = $this->pm;
        return $project_manager ? $project_manager->name : null;
    }
    
    public function getMarketShareAttribute()
    {
        if ($this->transaction_type_id == 1) {
            return $this->prospectTmb->sum('tmb.market_share');
        } else if ($this->transaction_type_id == 2) {
            return $this->prospectPbth->sum('market_share');
        }
    }

    public function getSalesPlanAttribute()
    {
        $sales = Sales::where('prospect_id', $this->id);
        return $sales->sum('value');
    }

    public function scopeMarketShareByCustomer($query, $customer)
    {
        $data = $query->whereHas('amsCustomer', function ($query) use ($customer) {
            $query->where('customer_id', $customer);
        })->get();

        return $data->sum('market_share');
    }

    public function getRegistrationAttribute()
    {
        if ($this->transaction_type_id == 1) {
            $tmb = $this->prospectTmb->first()->tmb;
            $ac_type = $tmb->acType ? $tmb->acType->name : '-';
            $engine = $tmb->engine ? $tmb->engine->name : '-';
            $apu = $tmb->apu ? $tmb->apu->name : '-';
            $component = $tmb->component ? $tmb->component->name : '-';

            $registration = "{$ac_type}/{$engine}/{$apu}/{$component}";
        } else {
            $registration = $this->prospectPbth ? $this->prospectPbth->first()->acType->name : '-';
        }

        return $registration;
    }

    public function scopeMarketYearAgo($query)
    {
        $data = $query->where('year', Carbon::now()->format('Y'))->get();

        return $data->sum('market_share');
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
