<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\Tenant;

class Payroll extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'tenant_id',
        'employee_id',
        'period_start',
        'period_end',
        'base_amount',
        'bonus',
        'deductions',
        'total_amount',
        'status',
        'details',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'details' => 'array',
        'base_amount' => 'decimal:2',
        'bonus' => 'decimal:2',
        'deductions' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(\App\Models\MasterData\Employee::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
