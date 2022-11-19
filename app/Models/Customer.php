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
        'status',
    ];

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const STATUS_ARRAY = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'Inactive',
    ];

    public function scopeSearchProspect($query, $search)
    {
        $query->when($search, function ($query) use ($search) {
            $query->where('name', 'LIKE', "%$search%")
                ->orWhere('code', 'LIKE', "%$search%")
                ->orWhereHas('amsCustomers', function ($query) use ($search) {
                    $query->whereRelation('ams', 'initial', 'LIKE', "%$search%")
                        ->orWhereHas('prospects', function ($query) use ($search) {
                            $query->where('year', 'LIKE', "%$search%")
                                ->orWhereRelation('transactionType', 'name', 'LIKE', "%$search%")
                                ->orWhereRelation('prospectType', 'name', 'LIKE', "%$search%")
                                ->orWhereRelation('strategicInitiative', 'name', 'LIKE', "%$search%")
                                ->orWhereRelation('pm', 'name', 'LIKE', "%$search%")
                                ->orWhereRelation('prospectType', 'name', 'LIKE', "%$search%");
                        });
                });
        });
    }

    public function scopeFilterProspect($query, $filter)
    {
        $query->when($filter, function ($query) use ($filter) {
            $query->whereHas('amsCustomers', function ($query) use ($filter) {
                $query->whereHas('prospects', function ($query) use ($filter) {
                    $query->where('year', $filter);
                });
            });
        });
    }

    public function scopeUserProspect($query, $user)
    {
        $query->when($user->hasRole('AMS'), function ($query) use ($user) {   
            $query->whereHas('amsCustomers', function ($query) use ($user) {
                $query->where('ams_id', $user->ams->id);
            });
        });
    }

    public function scopeSortProspect($query, $user)
    {
        // code here..
    }

    public function getStatusAttribute()
    {
        return self::STATUS_ARRAY[$this->is_active];
    }

    public function scopeSearch($query, $search)
    {
        $query->when($search, function ($query) use ($search) {
            if (strtolower($search) == 'active') {
                $query->where('is_active', 1);
            } else if (strtolower($search) == 'inactive') {
                $query->where('is_active', 0);
            } else {
                $query->where('code', 'LIKE', "%{$search}%")
                    ->orWhere('name', 'LIKE', "%{$search}%")
                    ->orWhereHas('country', function ($query) use ($search) {
                        $query->where('name', 'LIKE', "%{$search}%")
                            ->orWhereRelation('region', 'name', 'LIKE', "%{$search}%");
                    });
            }
        });
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
        return $this->hasMany(AMSCustomer::class, 'customer_id', 'id');
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
