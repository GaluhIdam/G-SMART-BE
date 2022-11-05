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

    public function scopeSearch($query, $search)
    {
        $query->when($search, function ($query) use ($search) {
            $query->where('initial', 'LIKE', "%{$search}%")
                ->orWhereRelation('user', 'name', 'LIKE', "%{$search}%");
        });
    }

    public function scopeSort($query, $order, $by)
    {
        $query->when(($order && $by), function ($query) use ($order, $by) {
            if ($order  == 'user') {
                $query->withAggregate('user', 'name')
                    ->orderBy('user_name', $by);
            } else {
                $query->orderBy('initial', $by);
            }
        }) ;
    }

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
