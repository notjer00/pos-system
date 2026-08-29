<?php

namespace App\Services;

use App\Models\SalesTransaction;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransactionReversalService
{
    public function void(SalesTransaction $transaction, User $user, ?string $note = null): SalesTransaction
    {
        $this->authorizeVoid($user);

        return DB::transaction(function () use ($transaction, $user, $note) {
            if (! $transaction->isCompleted()) {
                throw ValidationException::withMessages([
                    'transaction' => 'Only completed transactions can be voided.',
                ]);
            }

            // Check if within void window (same day)
            if (! $transaction->created_at->isToday()) {
                throw ValidationException::withMessages([
                    'transaction' => 'Voiding is only allowed for same-day transactions.',
                ]);
            }

            // Update transaction status
            $transaction->update(['status' => 'voided']);

            // Reverse stock for each line item
            foreach ($transaction->lineItems as $lineItem) {
                $variant = $lineItem->productVariant;
                $variant->increment('current_stock', $lineItem->quantity);

                StockMovement::create([
                    'product_variant_id' => $variant->id,
                    'type' => 'void_reversal',
                    'quantity_change' => $lineItem->quantity,
                    'note' => $note ?? "Void of transaction #{$transaction->id}",
                    'created_by' => $user->id,
                ]);
            }

            // Mark commission logs as voided
            $transaction->commissionLogs()->update(['is_voided' => true]);

            return $transaction->fresh();
        });
    }

    public function refund(SalesTransaction $transaction, User $user, ?string $note = null): SalesTransaction
    {
        $this->authorizeRefund($user);

        return DB::transaction(function () use ($transaction, $user, $note) {
            if (! $transaction->isCompleted()) {
                throw ValidationException::withMessages([
                    'transaction' => 'Only completed transactions can be refunded.',
                ]);
            }

            // Update transaction status
            $transaction->update(['status' => 'refunded']);

            // Reverse stock for each line item
            foreach ($transaction->lineItems as $lineItem) {
                $variant = $lineItem->productVariant;
                $variant->increment('current_stock', $lineItem->quantity);

                StockMovement::create([
                    'product_variant_id' => $variant->id,
                    'type' => 'refund_reversal',
                    'quantity_change' => $lineItem->quantity,
                    'note' => $note ?? "Refund of transaction #{$transaction->id}",
                    'created_by' => $user->id,
                ]);
            }

            // Mark commission logs as voided
            $transaction->commissionLogs()->update(['is_voided' => true]);

            return $transaction->fresh();
        });
    }

    private function authorizeVoid(User $user): void
    {
        // Both cashiers and admins can void
        if (! $user->isCashier() && ! $user->isAdmin()) {
            abort(403, 'Unauthorized: Only cashiers and admins can void transactions.');
        }
    }

    private function authorizeRefund(User $user): void
    {
        // Only admins can refund
        if (! $user->isAdmin()) {
            abort(403, 'Unauthorized: Only admins can process refunds.');
        }
    }
}
