<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Traits\HasBranch;
use App\Models\Tenant;

class Transaction extends Model
{
    use HasFactory, HasUuids, HasBranch;

    protected $guarded = ['id'];

    protected $appends = ['remaining_balance'];

    public function getRemainingBalanceAttribute()
    {
        return max(0, $this->total_price - $this->amount_paid);
    }

    public function isFullyPaid()
    {
        return $this->payment_status === 'paid' && $this->remaining_balance <= 0;
    }

    public function customer()
    {
        return $this->belongsTo(\App\Models\MasterData\Customer::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function productionAssignments()
    {
        return $this->hasMany(ProductionAssignment::class);
    }

    public function pointsHistory()
    {
        return $this->hasMany(PointsHistory::class);
    }
}
