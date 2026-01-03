<?php

namespace App\Observers;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        // Stock deduction is now handled in TransactionController
        // to support multi-item transactions.
    }

    /**
     * Handle the Transaction "updated" event.
     */
    public function updated(Transaction $transaction): void
    {
        // Handle cancelled transaction -> restore stock?
        if ($transaction->wasChanged('status') && $transaction->status === 'cancelled') {
            // Restore stock logic could go here
        }
    }
}
