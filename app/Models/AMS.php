<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AMS extends Model
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

    public function ams_customer()
    {
        return $this->hasMany(AMSCustomer::class);
    }

    public function ams_targets()
    {
        return $this->hasMany(AMSTarget::class, 'ams_id');
    }
}
