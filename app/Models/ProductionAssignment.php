<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ProductionAssignment extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'tenant_id',
        'transaction_id',
        'employee_id',
        'task_type',
        'qty',
        'rate_snapshot',
    ];

    protected $casts = [
        'qty' => 'decimal:2',
        'rate_snapshot' => 'decimal:2',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function employee()
    {
        return $this->belongsTo(\App\Models\MasterData\Employee::class);
    }
}
