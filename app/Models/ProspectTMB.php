<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProspectTMB extends Model
{
    use HasFactory;

    protected $table = 'prospect_tmb';

    protected $fillable = [
        'prospect_id',
        'tmb_id',
    ];

    public function tmb()
    {
        return $this->belongsTo(TMB::class, 'tmb_id');
    }

    public function prospect()
    {
        return $this->belongsTo(Prospect::class, 'prospect_id');
    }
}
