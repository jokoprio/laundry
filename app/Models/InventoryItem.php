<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Traits\HasBranch;

class InventoryItem extends Model
{
    use HasFactory, HasUuids, HasBranch;

    protected $guarded = ['id'];
}
