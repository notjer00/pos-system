<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-lg font-semibold" style="color: hsl(var(--foreground));">Recent Transactions</h1>
            <p class="text-sm mt-0.5" style="color: hsl(var(--muted-foreground));">Today's sales activity</p>
        </div>
    </div>

    {{-- Search Section --}}
    <div class="tx-filter-panel">
        <div class="flex flex-col gap-1.5">
            <label class="text-xs font-medium" style="color: hsl(var(--muted-foreground));">Search</label>
            <div class="relative max-w-md">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4" style="color: hsl(var(--muted-foreground));" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search by Transaction ID..."
                    class="input-field pl-10"
                >
            </div>
        </div>
    </div>

    {{-- Transactions Table --}}
    @if ($transactions->count() > 0)
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full tx-table">
                    <thead>
                        <tr>
                            <th>Transaction</th>
                            <th>Time</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $transaction)
                            <tr>
                                <td>
                                    <span class="font-semibold" style="color: hsl(var(--foreground));">#{{ $transaction->id }}</span>
                                </td>
                                <td>
                                    <span class="text-sm" style="color: hsl(var(--muted-foreground));">{{ $transaction->created_at->format('H:i:s') }}</span>
                                </td>
                                <td>
                                    <span class="text-sm" style="color: hsl(var(--muted-foreground));">{{ $transaction->lineItems->sum('quantity') }}</span>
                                </td>
                                <td>
                                    <span class="text-sm font-semibold" style="color: hsl(var(--foreground));">&#8369;{{ number_format($transaction->total_amount, 2) }}</span>
                                </td>
                                <td>
                                    @if ($transaction->status === 'completed')
                                        <span class="badge-success text-[11px]">
                                            <span class="w-1.5 h-1.5 rounded-full mr-1.5" style="background: #5cc980;"></span>
                                            Completed
                                        </span>
                                    @elseif ($transaction->status === 'voided')
                                        <span class="badge-warning text-[11px]">
                                            <span class="w-1.5 h-1.5 rounded-full mr-1.5" style="background: #d4b060;"></span>
                                            Voided
                                        </span>
                                    @elseif ($transaction->status === 'refunded')
                                        <span class="badge-destructive text-[11px]">
                                            <span class="w-1.5 h-1.5 rounded-full mr-1.5" style="background: #f0a0a0;"></span>
                                            Refunded
                                        </span>
                                    @else
                                        <span class="badge text-[11px]">{{ ucfirst($transaction->status) }}</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    @php
                                        $canVoid = $transaction->isCompleted() && $transaction->created_at->isToday() && $transaction->cashier_id === auth()->id();
                                    @endphp
                                    @if ($canVoid)
                                        <button wire:click="openVoidModal({{ $transaction->id }})" class="tx-action-void">
                                            Void
                                        </button>
                                    @else
                                        <span class="text-xs" style="color: hsl(var(--muted-foreground));">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    @else
        {{-- Empty State --}}
        <div class="card">
            <div class="tx-empty">
                <div class="tx-empty-icon">
                    <svg class="w-8 h-8" style="color: hsl(var(--muted-foreground));" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-sm font-semibold" style="color: hsl(var(--muted-foreground));">No transactions today</h3>
                <p class="text-xs mt-1" style="color: hsl(var(--muted-foreground));">Completed transactions will appear here</p>
            </div>
        </div>
    @endif

    {{-- Void Modal --}}
    <div x-show="$wire.showVoidModal" x-cloak style="display: none;" x-transition.opacity.duration.200ms>
        <div class="dialog-overlay" wire:click="closeModals"></div>
        <div class="dialog-content max-w-md">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold" style="color: hsl(var(--foreground));">Void Transaction</h3>
                <button wire:click="closeModals" class="btn-icon-sm" style="color: hsl(var(--muted-foreground));">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="space-y-4">
                {{-- Transaction Details --}}
                <div class="tx-modal-details">
                    <div class="flex justify-between">
                        <span class="text-sm" style="color: hsl(var(--muted-foreground));">Transaction</span>
                        <span class="text-sm font-medium" style="color: hsl(var(--foreground));">#{{ $selectedTransaction?->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm" style="color: hsl(var(--muted-foreground));">Time</span>
                        <span class="text-sm font-medium" style="color: hsl(var(--foreground));">{{ $selectedTransaction?->created_at?->format('H:i:s') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm" style="color: hsl(var(--muted-foreground));">Total</span>
                        <span class="text-sm font-semibold" style="color: hsl(var(--foreground));">&#8369;{{ number_format($selectedTransaction?->total_amount ?? 0, 2) }}</span>
                    </div>
                </div>

                {{-- Reason --}}
                <div class="space-y-2">
                    <label class="text-sm font-medium" style="color: hsl(var(--muted-foreground));">Reason / Note</label>
                    <textarea
                        wire:model="voidNote"
                        rows="3"
                        class="input-field w-full"
                        placeholder="Enter reason for void..."
                    ></textarea>
                </div>

                {{-- Actions --}}
                <div class="tx-modal-actions">
                    <button type="button" wire:click="closeModals" class="btn-secondary">
                        Cancel
                    </button>
                    <button type="button" wire:click="processVoid" class="btn-warning">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Void Transaction
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
