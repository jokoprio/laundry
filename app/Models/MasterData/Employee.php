<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Traits\HasBranch;

class Employee extends Model
{
    use HasFactory, HasUuids, HasBranch;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'user_id',
        'name',
        'position',
        'phone',
        'salary_type',
        'base_salary',
        'rate_per_unit',
    ];

    protected $casts = [
        'base_salary' => 'decimal:2',
        'rate_per_unit' => 'decimal:2',
    ];

    public function payrolls()
    {
        return $this->hasMany(\App\Models\Payroll::class);
    }

    public function productionAssignments()
    {
        return $this->hasMany(\App\Models\ProductionAssignment::class);
    }
}
