<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Models\SalesRequirement;
use App\Models\SalesLevel;
use App\Models\Line;

class Sales extends Model
{
    use HasFactory;

    const STATUS_OPEN = 1;
    const STATUS_CLOSE_IN = 2;
    const STATUS_CLOSED_SALES = 3;
    const STATUS_CANCEL = 4;
    const IS_RKAP = 1;
    const ADDITIONAL = 0;

    const STATUS_ARRAY = [
        self::STATUS_OPEN => 'Open',
        self::STATUS_CLOSE_IN => 'Closed',
        self::STATUS_CLOSED_SALES => 'Close in',
        self::STATUS_CANCEL => 'Cancel',
    ];

    const RKAP_ARRAY = [
        self::IS_RKAP => 'RKAP',
        self::ADDITIONAL => 'Additional',
    ];

    protected $fillable = [
        'customer_id',
        'prospect_id',
        'maintenance_id',
        'ac_reg',
        'value',
        'tat',
        'start_date',
        'so_number',
        'is_rkap',
    ];

    protected $hidden = [
        'prospect',
        'salesRequirements',
        'salesLevel',
    ];

    protected $appends = [
        'status',
        'other',
        'registration',
        'type',
        'level',
        'progress',
        'contact_persons',
        'level4',
        'level3',
        'level2',
        'level1',
        'upgrade_level',
        'month_sales',
    ];

    public function scopeLevel($query, $level)
    {
        $query->whereRelation('salesLevel', 'level_id', $level);
    }

    public function scopeOpen($query)
    {
        $query->whereRelation('salesLevel', 'status', 1);
    }

    public function scopeClosed($query)
    {
        $query->whereRelation('salesLevel', 'status', 2);
    }

    public function scopeCloseIn($query)
    {
        $query->whereRelation('salesLevel', 'status', 3);
    }

    public function scopeCancel($query)
    {
        $query->whereRelation('salesLevel', 'status', 4);
    }

    public function scopeRkap($query)
    {
        $query->where('is_rkap', 1);
    }

    public function scopeThisYear($query)
    {
        $query->whereYear('start_date', Carbon::now()->format('Y'))
            ->whereYear('end_date', Carbon::now()->format('Y'));
    }

    public function scopeUser($query, $user)
    {
        $query->when($user->hasRole('AMS'), function ($query) use ($user) {
            $query->where('ams_id', $user->ams->id);
        });
    }

    public function scopeCustomer($query, $customer)
    {
        $query->where('customer_id', $customer);
    }

    public function scopeSearch($query, $search)
    {
        $query->when($search, function ($query) use ($search) {
            if (str_contains(strtolower($search), 'rkap')) {
                $query->where('is_rkap', 1);
            } else if (str_contains(strtolower($search), 'additional')) {
                $query->where('is_rkap', 0);
            } else if (!strcasecmp($search, 'open')) {
                $query->whereRelation('salesLevel', 'status', 1);
            } else if (!strcasecmp($search, 'cancel')) {
                $query->whereRelation('salesLevel', 'status', 4);
            } else if (str_contains(strtolower($search), 'close')) {
                if (!strcasecmp($search, 'closed sales')) {
                    $query->whereRelation('salesLevel', 'status', 3);
                } else if (!strcasecmp($search, 'close in')) {
                    $query->whereRelation('salesLevel', 'status', 2);
                } else {
                    $query->whereRelation('salesLevel', 'status', 2)
                        ->orWhereRelation('salesLevel', 'status', 3);
                }
            } else if (str_contains(strtolower($search), 'level')) {
                $query->whereRelation('salesLevel', 'level_id', substr($search, -1));
            } else {
                $query->whereRelation('customer', 'name', 'LIKE', "%$search%")
                    ->orWhereRelation('product', 'name', 'LIKE', "%$search%")
                    ->orWhereRelation('acType', 'name', 'LIKE', "%$search%")
                    ->orWhereRelation('engine', 'name', 'LIKE', "%$search%")
                    ->orWhereRelation('apu', 'name', 'LIKE', "%$search%")
                    ->orWhereRelation('component', 'name', 'LIKE', "%$search%")
                    ->orWhereRelation('maintenance', 'name', 'LIKE', "%$search%")
                    ->orWhereRelation('hangar', 'name', 'LIKE', "%$search%")
                    ->orWhereRelation('salesLevel', 'level_id', 'LIKE', "%$search%")
                    ->orWhere('ac_reg', 'LIKE', "%$search%")
                    ->orWhere('value', 'LIKE', "%$search%")
                    ->orWhere('value', 'LIKE', "%$search%")
                    ->orWhere('tat', 'LIKE', "%$search%")
                    ->orWhere('start_date', 'LIKE', "%$search%")
                    ->orWhere('end_date', 'LIKE', "%$search%")
                    ->orWhereHas('prospect', function ($query) use ($search) {
                        $query->whereHas('transactionType', function ($query) use ($search) {
                            $query->where('name', 'LIKE', "%$search%");
                        });
                    });
            }
        });
    }

    public function scopeFilter($query, array $filters)
    {
        $start_date = $filters['start_date'];
        $end_date = $filters['end_date'];
        $type = $filters['type'];
        $customer = $filters['customer'];
        $product = $filters['product'];
        $registration = $filters['registration'];
        $acReg = $filters['acReg'];
        $other = $filters['other'];
        $level = $filters['level'];
        $progress = $filters['progress'];
        $status = $filters['status'];

        $query->when(($start_date && $end_date), function ($query) use ($start_date, $end_date) {
            $query->whereDate('start_date', '>=', Carbon::parse($start_date)->format('Y-m-d'))
                ->whereDate('end_date', '<=', Carbon::parse($end_date)->format('Y-m-d'));
        });
        
        $query->when($type, function ($query) use ($type) {
            $query->whereRelation('prospect', 'transaction_type_id', $type);
        });

        $query->when($customer, function ($query) use ($customer) {
            $query->whereRelation('customer', 'id', $customer);
        });

        $query->when($product, function ($query) use ($product) {
            $query->whereRelation('product', 'id', $product);
        });

        // TODO: registration??

        $query->when($acReg, function ($query) use ($acReg) {
            $query->where('acReg', $acReg);
        });

        $query->when($other, function ($query) use ($other) {
            $query->where('is_rkap', $other);
        });

        $query->when($level, function ($query) use ($level) {
            $query->whereRelation('salesLevel', 'level_id', $level);
        });

        $query->when($status, function ($query) use ($status) {
            $query->whereRelation('salesLevel', 'status', $status);
        });
    }

    public function scopeSort($query, $order, $by)
    {
        $query->when(($order && $by), function ($query) use ($order, $by) {
            if ($order == 'customer') {
                $query->withAggregate('customer', 'name')
                    ->orderBy('customer_name', $by);
            } else if ($order == 'product') {
                $query->withAggregate('product', 'name')
                    ->orderBy('product_name', $by);
            } else if ($order == 'registration') {
                $query->withAggregate('acType', 'name')
                    ->withAggregate('engine', 'name')
                    ->withAggregate('apu', 'name')
                    ->withAggregate('component', 'name')
                    ->orderBy('ac_type_name', $by)
                    ->orderBy('engine_name', $by)
                    ->orderBy('apu_name', $by)
                    ->orderBy('component_name', $by);
            } else if ($order == 'acReg') {
                $query->orderBy('ac_reg', $by);
            } else if ($order == 'other') {
                $query->orderBy('is_rkap', $by);
            } else if ($order == 'type') {
                $query->withAggregate('prospect', 'transaction_type_id')
                    ->orderBy('prospect_transaction_type_id', $by);
            } else if ($order == 'level') {
                $query->withAggregate('salesLevel', 'level_id')
                    ->orderBy('sales_level_level_id', $by);
            } else if ($order == 'status') {
                $query->withAggregate('salesLevel', 'status')
                    ->orderBy('sales_level_status', $by);
            } else if ($order == 'progress') {
                $query->withCount('requirementDone')
                    ->orderBy('requirement_done_count', $by);
            } else if ($order == 'id') {
                $query->orderBy('id', $by);
            }
        });
    }

    public function setRequirement($requirement_id)
    {
        $requirement = $this->salesRequirements->where('requirement_id', $requirement_id);

        if ($requirement->isEmpty()) {
            $requirement = new SalesRequirement;
            $requirement->sales_id = $this->id;
            $requirement->requirement_id = $requirement_id;
            $requirement->status = 1;
            $requirement->save();
        } else {
            if ($requirement->count() > 1) {
                foreach ($requirement as $item) {
                    if ($requirement->count() > 1) {
                        $item->delete();
                    }
                }
            }
            $requirement = $requirement->first();
            $requirement->status = 1;
            $requirement->push();
        }

        return $requirement;
    }

    public function getMonthSalesAttribute()
    {
        return Carbon::parse($this->start_date)->format('F');
    }

    public function getStatusAttribute()
    {
        return self::STATUS_ARRAY[$this->salesLevel->status];
    }

    public function getOtherAttribute()
    {
        return self::RKAP_ARRAY[$this->is_rkap ?? 0];
    }

    // TODO: confirmation needed!!
    public function getRegistrationAttribute()
    {
        $ac_type = $this->acType ? trim($this->acType->name) : '-';
        $engine = $this->engine ? trim($this->engine->name) : '-';
        $apu = $this->apu ? trim($this->apu->name) : '-';
        $component = $this->component ? trim($this->component->name) : '-';

        if (in_array($this->prospect->transaction_type_id, [1,2])) {    
            $registration = "{$ac_type}/{$engine}/{$apu}/{$component}";
        } else {
            $registration = $ac_type;
        }

        return $registration;
    }

    public function getTypeAttribute()
    {
        return $this->prospect->transactionType->name;
    }

    public function getLevelAttribute()
    {
        return $this->salesLevel->level_id;
    }

    public function getProgressAttribute()
    {
        return $this->requirementDone()->count() * 10;
    }

    public function getContactPersonsAttribute()
    {
        return $this->customer->contactPersons;
    }

    public function getLevel4Attribute()
    {
        $requirements = $this->salesRequirements->whereIn('requirement_id', [1, 2, 3]);
        $level4 = new Collection();
        
        foreach ($requirements as $item) {
            if ($item->requirement_id == 1) {
                $data = $this->contact_persons->sortByDesc('updated_at')->take(10)->values();
                if ($data->isNotEmpty()) {
                    $last_update = Carbon::parse($this->customer->latestCP->updated_at)->format('Y-m-d H:i');
                } else {
                    $last_update = null;
                }
            } else {
                $data = $item->files;
                if ($data->isNotEmpty()) {
                    $last_update = Carbon::parse($item->latestFile->updated_at)->format('Y-m-d H:i');
                } else {
                    $last_update = null;
                }
            }

            $level4->push((object)[
                'sequence' => $item->requirement_id,
                'name' => $item->requirement->requirement,
                'status' => $item->status,
                'lastUpdate' => $last_update,
                'data' => $data,
            ]);
        }

        return collect($level4)->sortBy('sequence')->values();
    }

    public function getLevel3Attribute()
    {
        $requirements = $this->salesRequirements->whereIn('requirement_id', [4, 5, 6]);
        $level3 = new Collection();
        
        foreach ($requirements as $item) {;
            $data = $item->files;
            if ($data->isNotEmpty()) {
                $last_update = Carbon::parse($item->latestFile->updated_at)->format('Y-m-d H:i');
            } else {
                $last_update = null;
            }

            $level3->push((object)[
                'sequence' => $item->requirement_id,
                'name' => $item->requirement->requirement,
                'status' => $item->status,
                'lastUpdate' => $last_update,
                'data' => $data,
            ]);
        }

        return collect($level3)->sortBy('sequence')->values();
    }

    public function getLevel2Attribute()
    {
        $requirements = $this->salesRequirements->whereIn('requirement_id', [7, 8]);
        $level2 = new Collection();
        
        foreach ($requirements as $item) {;
            if ($item->requirement_id == 8) {
                $data = $this->line;
                if ($data) {
                    $data = [
                        'hangar' => $this->hangar->name,
                        'line' => $this->line,
                        'tat' => $this->tat,
                        'registration' => $this->registration,
                        'startDate' => Carbon::parse($this->start_date)->format('d-m-Y'),
                        'endDate' => Carbon::parse($this->end_date)->format('d-m-Y'),
                    ];
                    $last_update = Carbon::parse($this->updated_at)->format('Y-m-d H:i');
                } else {
                    $last_update = null;
                }
            } else {
                $data = $item->files;
                if ($data->isNotEmpty()) {
                    $last_update = Carbon::parse($item->latestFile->updated_at)->format('Y-m-d H:i');
                } else {
                    $last_update = null;
                }
            }

            $level2->push((object)[
                'sequence' => $item->requirement_id,
                'name' => $item->requirement->requirement,
                'status' => $item->status,
                'lastUpdate' => $last_update,
                'data' => $data,
            ]);
        }

        return collect($level2)->sortBy('sequence')->values();
    }

    public function getLevel1Attribute()
    {
        $requirements = $this->salesRequirements->whereIn('requirement_id', [9, 10]);
        $level1 = new Collection();

        foreach ($requirements as $item) {;
            if ($item->requirement_id == 10) {
                $data = $this->so_number;
                if ($data) {
                    $last_update = Carbon::parse($this->updated_at)->format('Y-m-d H:i');
                } else {
                    $last_update = null;
                }
            } else {
                $data = $item->files;
                if ($data->isNotEmpty()) {
                    $last_update = Carbon::parse($item->latestFile->updated_at)->format('Y-m-d H:i');
                } else {
                    $last_update = null;
                }
            }

            $level1->push((object)[
                'sequence' => $item->requirement_id,
                'name' => $item->requirement->requirement,
                'status' => $item->status,
                'lastUpdate' => $last_update,
                'data' => $data,
            ]);
        }

        return collect($level1)->sortBy('sequence')->values();
    }

    public function getUpgradeLevelAttribute()
    {
        $requirements = Requirement::where('level_id', $this->level)->pluck('id');
        $requirement_done = $this->requirementDone->whereIn('requirement_id', $requirements);
        
        return ($requirement_done->count() == $requirements->count());
    }

    public function requirementDone()
    {
        return $this->hasMany(SalesRequirement::class)->where('status', 1);
    }

    public function salesReschedule()
    {
        return $this->hasOne(SalesReschedule::class);
    }

    public function salesRequirements()
    {
        return $this->hasMany(SalesRequirement::class);
    }

    public function salesUpdates()
    {
        return $this->hasMany(SalesUpdate::class);
    }

    public function salesReject()
    {
        return $this->hasOne(SalesReject::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function prospect()
    {
        return $this->belongsTo(Prospect::class, 'prospect_id');
    }

    public function maintenance()
    {
        return $this->belongsTo(Maintenance::class, 'maintenance_id');
    }

    public function salesHistories()
    {
        return $this->hasMany(SalesHistory::class);
    }

    public function hangar()
    {
        return $this->belongsTo(Hangar::class, 'hangar_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function acType()
    {
        return $this->belongsTo(AircraftType::class, 'ac_type_id');
    }

    public function component()
    {
        return $this->belongsTo(Component::class, 'component_id');
    }

    public function engine()
    {
        return $this->belongsTo(Engine::class, 'engine_id');
    }

    public function apu()
    {
        return $this->belongsTo(Apu::class, 'apu_id');
    }

    public function salesLevel()
    {
        return $this->hasOne(SalesLevel::class);
    }

    public function ams()
    {
        return $this->belongsTo(AMS::class, 'ams_id');
    }

    public function line()
    {
        return $this->belongsTo(Line::class, 'line_id');
    }
}
