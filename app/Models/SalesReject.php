<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReject extends Model
{
    use HasFactory;
    protected $fillable = [
        'sales_id',
        'category',
        'reason',
        'competitor',
    ];

    public function sales_id()
    {
        return $this->belongsTo(Sales::class, 'sales_id');
    }
}
