<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;
    protected $fillable = [
        'sales_requirement_id',
        'path',
    ];

    public function salesRequirement()
    {
        return $this->belongsTo(SalesRequirement::class, 'sales_requirement_id');
    }
}
