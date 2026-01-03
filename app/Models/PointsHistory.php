<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\Transaction;

class PointsHistory extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'points_history';

    protected $fillable = [
        'tenant_id',
        'customer_id',
        'transaction_id',
        'points',
        'type',
        'description',
    ];

    public function customer()
    {
        return $this->belongsTo(\App\Models\MasterData\Customer::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
