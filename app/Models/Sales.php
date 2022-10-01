<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Collection;

use App\Models\SalesRequirement;

class Sales extends Model
{
    use HasFactory;

    const STATUS_OPEN = 1;
    const STATUS_CLOSE_IN = 2;
    const STATUS_CLOSED_SALES = 3;
    const STATUS_CANCEL = 4;
    const IS_RKAP = 1;
    const NOT_RKAP = 0;
    const LEVEL_1 = 1;
    const LEVEL_2 = 2;
    const LEVEL_3 = 3;
    const LEVEL_4 = 4;

    const STATUS_ARRAY = [
        self::STATUS_OPEN => 'Open',
        self::STATUS_CLOSE_IN => 'Close in',
        self::STATUS_CLOSED_SALES => 'Closed Sales',
        self::STATUS_CANCEL => 'Cancel',
    ];

    const RKAP_ARRAY = [
        self::IS_RKAP => 'RKAP',
        self::NOT_RKAP => 'NOT-RKAP',
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
        'tmb_properties',
        'type',
        'level',
        'progress',
        'contact_persons',
        'level4',
        'level3',
        'level2',
        'level1',
    ];

    public function getStatusAttribute()
    {
        return self::STATUS_ARRAY[$this->salesLevel->status];
    }

    public function getOtherAttribute()
    {
        return self::RKAP_ARRAY[$this->is_rkap];
    }

    public function getTMBPropertiesAttribute()
    {
        return $this->acType->name.'/'.$this->engine->name.'/'.$this->apu->name.'/'.$this->component->name;
    }

    public function getTypeAttribute()
    {
        return $this->prospect->transactionType->name;
    }

    public function getLevelAttribute()
    {
        return $this->salesLevel->level->level;
    }

    public function getProgressAttribute()
    {
        $progress = 0;

        $levels = [
            $this->level4,
            $this->level3,
            $this->level2,
            $this->level1,
        ];

        foreach ($levels as $level) {
            foreach ($level as $requirement) {
                if ($requirement->status == 1) $progress += 10;
            }
        }

        return $progress;
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
                $data = $this->contact_persons->where('status', 1)->values();
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
                $last_update = Carbon::parse($item->lastFile->updated_at)->format('Y-m-d H:i');
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
                $data = $this->hangar;
                if ($data) {
                    // TODO: perlu konfirmasi -> requirement slot hangar dapet dari mana?
                    $last_update = Carbon::parse($this->updated_at)->format('Y-m-d H:i');
                } else {
                    $last_update = null;
                }
            } else {
                $data = $item->files;
                if ($data->isNotEmpty()) {
                    $last_update = Carbon::parse($item->lastFile->updated_at)->format('Y-m-d H:i');
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

    // query untuk global search tabel salesplan
    public function scopeSearch($query, $search)
    {
        $query->when($search, function ($query) use ($search) {
            if (str_contains(strtolower($search), 'rkap')) {
                $query->where('is_rkap', 1);
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
        $query->whereHas('prospect', function ($query) use ($user) {
            $query->where('pm_id', $user);
        });
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
            } else if ($order == 'level') {
                $query->withAggregate('salesLevel', 'level_id')
                    ->orderBy('sales_level_level_id', $by);
            } else if ($order == 'progress') {
                if (!strcasecmp($by, 'asc')) {
                    $query->withAggregate('salesLevel', 'level_id')
                        ->orderBy('sales_level_level_id', 'desc');
                } else if (!strcasecmp($by, 'desc')) {
                    $query->withAggregate('salesLevel', 'level_id')
                        ->orderBy('sales_level_level_id', 'asc');
                }
            } else if ($order == 'status') {
                $query->withAggregate('salesLevel', 'status')
                    ->orderBy('sales_level_status', $by);
            } else if ($order == 'id') {
                $query->orderBy('id', $by);
            }
        });
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
        return $this->hasOne(SalesLevel::class); // TODO perlu konfirmasi (diskusi)
    }
}
