<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProspectPBTH extends Model
{
    use HasFactory;

    protected $table = 'prospect_pbth';

    protected $fillable = [
        'prospect_id',
        'pbth_id',
        'product_id',
        'ac_type_id',
    ];

    public function pbth()
    {
        return $this->belongsTo(PBTH::class, 'pbth_id');
    }

    public function prospect()
    {
        return $this->belongsTo(Prospect::class, 'prospect_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function acType()
    {
        return $this->belongsTo(ACType::class, 'ac_type_id');
    }
}
