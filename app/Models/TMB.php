<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TMB extends Model
{
    use HasFactory;

    protected $table = 'tmb';

    protected $fillable = [
        'product_id',
        'ac_type_id',
        'component_id',
        'engine_id',
        'apu_id',
        'market_share',
        'remarks',
        'maintenance_id',
    ];

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function acTypeID()
    {
        return $this->belongsTo(ACTypeId::class, 'ac_type_id');
    }

    public function componentID()
    {
        return $this->belongsTo(Component::class, 'component_id');
    }

    public function engineID()
    {
        return $this->belongsTo(Engine::class, 'engine_id');
    }

    public function apuID()
    {
        return $this->belongsTo(Apu::class, 'apu_id');
    }

    public function maintenanceID()
    {
        return $this->belongsTo(Maintenance::class, 'maintenance_id');
    }
    
    public function prospectTMB()
    {
        return $this->hasMany(ProspectTMB::class, 'id');
    }
}
