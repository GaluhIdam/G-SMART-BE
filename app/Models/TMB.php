<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class tmb extends Model
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

    public function maintenance()
    {
        return $this->belongsTo(Maintenance::class, 'maintenance_id');
    }
    
    public function prospectTmb()
    {
        return $this->hasOne(ProspectTMB::class);
    }
}
