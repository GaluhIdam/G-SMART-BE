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
    ];

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

    public function getStatusAttribute()
    {
        $level = $this->salesLevel->firstWhere('level_id', $this->level);

        return self::STATUS_ARRAY[$level->status];
    }

    public function getOtherAttribute()
    {
        return self::RKAP_ARRAY[$this->is_rkap ?? 0];
    }

    public function getRegistrationAttribute()
    {
        $ac_type = $this->acType ? $this->acType->name : '-';
        $engine = $this->engine ? $this->engine->name : '-';
        $apu = $this->apu ? $this->apu->name : '-';
        $component = $this->component ? $this->component->name : '-';

        return "{$ac_type}/{$engine}/{$apu}/{$component}";
    }

    public function getTypeAttribute()
    {
        return $this->prospect->transactionType->name;
    }

    public function getLevelAttribute()
    {
        $levels = $this->salesLevel->sortBy('level_id');

        foreach ($levels as $item) {
            if ($item->status == 4) {
                return $item->level_id;
            } else if ($item->status == 3)  {
                if ($item->level_id != 1) {
                    continue;
                } else {
                    return $item->level_id;
                }
            } else if ($item->status == 2) {
                if ($item->level_id == 1) {
                    return $item->level_id;
                } else {
                    return $item->level_id - 1;
                }
            } else {
                if ($item->level_id != 4) {
                    continue;
                } else {
                    return $item->level_id;
                }
            }
        }
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
                $data = $this->contact_persons->values();
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
        // if ($this->level == 1) {
        //     return false;
        // }

        $requirements = Requirement::where('level_id', $this->level)->pluck('id');
        $requirement_done = SalesRequirement::where('sales_id', $this->id)
                                            ->where('status', 1)
                                            ->whereIn('requirement_id', $requirements)
                                            ->get();
        
        return ($requirement_done->count() == $requirements->count());
    }

    // query untuk global search tabel salesplan
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
                    ->orWhereRelation('salesLevel', 'level_id', 'LIKE', '%'.substr($search, -1).'%')
                    ->orWhere('ac_reg', 'LIKE', "%$search%")
                    ->orWhere('value', 'LIKE', "%$search%")
                    ->orWhereHas('prospect', function ($query) use ($search) {
                        $query->whereHas('transactionType', function ($query) use ($search) {
                            $query->where('name', 'LIKE', "%$search%");
                        });
                    });
            }
        });
    }

    // query untuk filtering data tabel salesplan
    public function scopeFilter($query, array $filters)
    {
        $start_date = $filters[0];
        $end_date = $filters[1];
        $type = $filters[2];

        $query->when(($start_date && $end_date), function ($query) use ($start_date, $end_date) {
            $query->whereDate('start_date', '>=', Carbon::parse($start_date)->format('Y-m-d'))
                ->whereDate('end_date', '<=', Carbon::parse($end_date)->format('Y-m-d'));
        });
        
        $query->when($type, function ($query) use ($type) {
            $query->whereRelation('prospect', 'transaction_type_id', $type);
        });
    }

    // query untuk get data salesplan by user
    public function scopeUser($query, $user)
    {
        if ($user->hasRole('TPC')) {
            $query->whereRelation('prospect', 'pm_id', $user->id);
        } else if ($user->hasRole('AMS')) {
            $query->where('ams_id', $user->ams->id);
        }
    }

    // query untuk sorting data tabel salesplan
    public function scopeSort($query, array $orders)
    {
        $order = $orders[0];
        $by = $orders[1];

        $query->when(($order && $by), function ($query) use ($order, $by) {
            if ($order == 'customer') {
                $query->withAggregate('customer', 'name')
                    ->orderBy('customer_name', $by);
            } else if ($order == 'product') {
                $query->withAggregate('product', 'name')
                    ->orderBy('product_name', $by);
            } else if ($order == 'properties') {
                $query->withAggregate('acType', 'name')
                    ->withAggregate('engine', 'name')
                    ->withAggregate('apu', 'name')
                    ->withAggregate('component', 'name')
                    ->orderBy('ac_type_name', $by)
                    ->orderBy('engine_name', $by)
                    ->orderBy('apu_name', $by)
                    ->orderBy('component_name', $by);
            } else if ($order == 'registration') {
                $query->orderBy('ac_reg', $by);
            } else if ($order == 'other') {
                $query->orderBy('is_rkap', $by);
            } else if ($order == 'type') {
                $query->withAggregate('prospect', 'transaction_type_id')
                    ->orderBy('prospect_transaction_type_id', $by);
            // TODO doesn't work bree...  accessor gak akan kebaca di query database!
            // } else if ($order == 'level') {
            //     $query->orderBy('level', $by);
            // } else if ($order == 'status') {
            //     $query->orderBy('status', $by);
            } else if ($order == 'progress') {
                $query->withCount('requirementDone')
                    ->orderBy('requirement_done_count', $by);
            } else if ($order == 'id') {
                $query->orderBy('id', $by);
            }
        });
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
        return $this->hasMany(SalesLevel::class);
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
