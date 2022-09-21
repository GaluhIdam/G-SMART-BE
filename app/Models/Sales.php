<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'prospect_id',
        'maintenance_id',
        'ac_reg',
        'value',
        'tat',
        'start_date',
        'so_number',
    ];

    public function getStatusAttribute($status)
    {
        if ($this->salesLevel->status == 1){
            $status = 'Open';
        }else if ($this->salesLevel->status == 2){
            $status = 'Closed';
        }else if ($this->salesLevel->status == 3){
            $status = 'Close in';
        }else {
            $status = 'Cancel';
        }

        return $status;
    }

    public function scopeOpens($query)
    {
        $query->whereHas('salesLevel', function ($query) {
            $query->where('status', 1);
        });
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

    public function histories()
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
