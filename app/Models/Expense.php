<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
<<<<<<< HEAD
use App\Models\Tenant;

class Expense extends Model
{
    use HasFactory, HasUuids;
=======
use App\Traits\HasBranch;

class Expense extends Model
{
    use HasFactory, HasUuids, HasBranch;
>>>>>>> 9513f9d9f392a3b2852b4daca22d48e1b98290df

    protected $fillable = [
        'tenant_id',
        'category',
        'amount',
        'date',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
