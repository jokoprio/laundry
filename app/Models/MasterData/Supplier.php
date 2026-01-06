<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Supplier extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = ['id'];

    public function purchaseOrders()
    {
        return $this->hasMany(\App\Models\PurchaseOrder::class);
    }
}
