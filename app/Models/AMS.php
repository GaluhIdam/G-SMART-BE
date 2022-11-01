<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ams extends Model
{
    use HasFactory;

    protected $table = 'ams';

    protected $fillable = [
        'initial',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function amsCustomers()
    {
        return $this->hasMany(AMSCustomer::class);
    }

    public function amsTargets()
    {
        return $this->hasMany(AMSTarget::class, 'ams_id');
    }

    public function sales()
    {
        return $this->hasMany(Sales::class);
    }
}
