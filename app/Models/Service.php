<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Traits\HasBranch;

class Service extends Model
{
    use HasFactory, HasUuids, HasBranch;

    protected $guarded = ['id'];

    public function materials()
    {
        return $this->hasMany(ServiceMaterial::class);
    }
}
