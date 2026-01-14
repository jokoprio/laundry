<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\DB;
use App\Traits\HasBranch;

use App\Traits\LogsActivity;

class PurchaseOrder extends Model
{
    use HasFactory, HasUuids, HasBranch, LogsActivity;

    protected $guarded = ['id'];

    protected $casts = [
        'due_date' => 'date',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function supplier()
    {
        return $this->belongsTo(MasterData\Supplier::class);
    }

    public function payments()
    {
        return $this->hasMany(PurchasePayment::class);
    }

    public function markAsReceived()
    {
        if ($this->status === 'received')
            return;

        DB::transaction(function () {
            foreach ($this->items as $poItem) {
                $inventoryItem = $poItem->inventoryItem;

                // Calculate Weighted Average Cost
                // New Avg = ((OldQty * OldCost) + (NewQty * NewCost)) / (OldQty + NewQty)
                $oldValue = $inventoryItem->stock * $inventoryItem->avg_cost;
                $newValue = $poItem->qty * $poItem->cost;
                $totalQty = $inventoryItem->stock + $poItem->qty;

                if ($totalQty > 0) {
                    $newAvgCost = ($oldValue + $newValue) / $totalQty;
                    $inventoryItem->avg_cost = $newAvgCost;
                }

                $inventoryItem->stock += $poItem->qty;
                $inventoryItem->save();
            }

            $this->update(['status' => 'received']);
        });
    }
}
