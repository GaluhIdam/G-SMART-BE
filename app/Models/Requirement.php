<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'level_id',
        'requirement',
    ];

    public function level_id()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }
}
