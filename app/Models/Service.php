<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Traits\HasBranch;

use App\Traits\LogsActivity;

class Service extends Model
{
    use HasFactory, HasUuids, HasBranch, LogsActivity;

    protected $guarded = ['id'];

    public function materials()
    {
        return $this->hasMany(ServiceMaterial::class);
    }
}
