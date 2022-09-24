<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Sales extends Model
{
    use HasFactory;

    const STATUS_OPEN = 1;
    const STATUS_CLOSED = 2;
    const STATUS_CLOSE_IN = 3;
    const STATUS_CANCEL = 4;
    const IS_RKAP = 1;
    const NOT_RKAP = 0;
    const LEVEL_1 = 1;
    const LEVEL_2 = 2;
    const LEVEL_3 = 3;
    const LEVEL_4 = 4;

    const STATUS_ARRAY = [
        self::STATUS_OPEN => 'Open',
        self::STATUS_CLOSED => 'Closed',
        self::STATUS_CLOSE_IN => 'Close in',
        self::STATUS_CANCEL => 'Cancel',
    ];

    const RKAP_ARRAY = [
        self::IS_RKAP => 'RKAP',
        self::NOT_RKAP => 'NOT-RKAP',
    ];

    const LEVEL_ARRAY = [
        self::LEVEL_1 => 100,
        self::LEVEL_2 => 75,
        self::LEVEL_3 => 50,
        self::LEVEL_4 => 25,
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
        return self::LEVEL_ARRAY[$this->salesLevel->level_id];
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
                if (!strcasecmp($search, 'closed')) {
                    $query->whereRelation('salesLevel', 'status', 2);
                } else if (!strcasecmp($search, 'close in')) {
                    $query->whereRelation('salesLevel', 'status', 3);
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

    // query untuk ordering data tabel salesplan
    public function scopeOrder($query, array $orders)
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

    public function salesReschedules()
    {
        return $this->hasMany(SalesReschedule::class);
    }

    public function salesRequirements()
    {
        return $this->hasMany(SalesRequirement::class);
    }

    public function salesUpdates()
    {
        return $this->hasMany(SalesUpdate::class);
    }

    public function salesRejects()
    {
        return $this->hasMany(SalesReject::class);
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
}
