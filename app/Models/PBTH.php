<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PBTH extends Model
{
    use HasFactory;

    protected $table = 'pbth';
    protected $guarded = ['id'];

    public function prospectPbth()
    {
        return $this->hasMany(ProspectPBTH::class);
    }
}
