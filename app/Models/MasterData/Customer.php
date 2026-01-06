<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
<<<<<<< HEAD

class Customer extends Model
{
    use HasFactory, HasUuids;
=======
use App\Traits\HasBranch;

class Customer extends Model
{
    use HasFactory, HasUuids, HasBranch;
>>>>>>> 9513f9d9f392a3b2852b4daca22d48e1b98290df

    protected $guarded = ['id'];

    public function membershipLevel()
    {
        return $this->belongsTo(\App\Models\MembershipLevel::class);
    }

    public function balanceHistories()
    {
        return $this->hasMany(\App\Models\CustomerBalanceHistory::class);
    }

    public function pointsHistory()
    {
        return $this->hasMany(\App\Models\PointsHistory::class);
    }

    public function coupons()
    {
        return $this->hasMany(\App\Models\CustomerCoupon::class);
    }
}
