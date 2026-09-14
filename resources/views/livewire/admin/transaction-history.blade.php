<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold" style="color: #e8e4dc;">Transaction History</h1>
            <p class="text-sm mt-1" style="color: #6a6560;">View and manage all sales transactions</p>
        </div>
    </div>

    {{-- Filters Section --}}
    <div class="glass-panel rounded-xl p-4">
        <div class="flex flex-col lg:flex-row lg:items-end gap-4">
            {{-- Date Range --}}
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-medium" style="color: #8a8580;">From</label>
                    <input type="date" wire:model="dateFrom" class="input-field w-40">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-medium" style="color: #8a8580;">To</label>
                    <input type="date" wire:model="dateTo" class="input-field w-40">
                </div>
            </div>

            {{-- Status Filter --}}
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-medium" style="color: #8a8580;">Status</label>
                <select wire:model.live="statusFilter" class="input-field w-40">
                    <option value="all">All Statuses</option>
                    <option value="completed">Completed</option>
                    <option value="voided">Voided</option>
                    <option value="refunded">Refunded</option>
                </select>
            </div>

            {{-- Search --}}
            <div class="flex-1 flex flex-col gap-1.5">
                <label class="text-xs font-medium" style="color: #8a8580;">Search</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4" style="color: #6a6560;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search by ID or cashier name..."
                        class="input-field pl-10"
                    >
                </div>
            </div>
        </div>
    </div>

    {{-- Transactions Table --}}
    @if ($transactions->count() > 0)
        <div class="glass-panel rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider" style="color: #8a8580;">Transaction</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider" style="color: #8a8580;">Date & Time</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider" style="color: #8a8580;">Cashier</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider" style="color: #8a8580;">Items</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider" style="color: #8a8580;">Total</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider" style="color: #8a8580;">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider" style="color: #8a8580;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $transaction)
                            <tr class="transition-colors duration-150" style="border-bottom: 1px solid rgba(255,255,255,0.04);"
                                onmouseover="this.style.background='rgba(255,255,255,0.03)';"
                                onmouseout="this.style.background='';">
                                <td class="px-6 py-4">
                                    <span class="font-semibold" style="color: #e8e4dc;">#{{ $transaction->id }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm" style="color: #c0bbb0;">{{ $transaction->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs" style="color: #6a6560;">{{ $transaction->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm" style="color: #c0bbb0;">{{ $transaction->cashier->name }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm" style="color: #c0bbb0;">{{ $transaction->lineItems->sum('quantity') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-semibold" style="color: #e8e4dc;">₱{{ number_format($transaction->total_amount, 2) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($transaction->status === 'completed')
                                        <span class="badge-success text-[11px]">
                                            <span class="w-1.5 h-1.5 rounded-full mr-1.5" style="background: #5cbfaa;"></span>
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
                                <td class="px-6 py-4 text-right">
                                    @php
                                        $canVoid = $transaction->isCompleted() && $transaction->created_at->isToday();
                                        $canRefund = $transaction->isCompleted();
                                    @endphp
                                    <div class="flex items-center justify-end gap-2">
                                        @if ($canVoid)
                                            <button wire:click="openVoidModal({{ $transaction->id }})"
                                                class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all duration-200"
                                                style="background: rgba(200,155,60,0.1); color: #d4b060; border: 1px solid rgba(200,155,60,0.2);"
                                                onmouseover="this.style.background='rgba(200,155,60,0.2)';"
                                                onmouseout="this.style.background='rgba(200,155,60,0.1)';">
                                                Void
                                            </button>
                                        @endif
                                        @if ($canRefund)
                                            <button wire:click="openRefundModal({{ $transaction->id }})"
                                                class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all duration-200"
                                                style="background: rgba(180,50,50,0.1); color: #f0a0a0; border: 1px solid rgba(180,50,50,0.2);"
                                                onmouseover="this.style.background='rgba(180,50,50,0.2)';"
                                                onmouseout="this.style.background='rgba(180,50,50,0.1)';">
                                                Refund
                                            </button>
                                        @endif
                                        @if ($transaction->status !== 'completed')
                                            <span class="text-xs" style="color: #6a6560;">(Reversed)</span>
                                        @endif
                                    </div>
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
        <div class="glass-panel rounded-xl">
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-4" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06);">
                    <svg class="w-8 h-8" style="color: #4a4540;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-sm font-semibold" style="color: #c0bbb0;">No transactions found</h3>
                <p class="text-xs mt-1" style="color: #6a6560;">Try adjusting your filters or date range</p>
            </div>
        </div>
    @endif

    {{-- Void Modal --}}
    @if ($showVoidModal && $selectedTransaction)
        <div class="dialog-overlay" wire:click="closeModals"></div>
        <div class="dialog-content max-w-md">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold" style="color: #e8e4dc;">Void Transaction</h3>
                <button wire:click="closeModals" class="btn-icon-sm" style="color: #6a6560;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="processVoid" class="space-y-4">
                {{-- Transaction Details --}}
                <div class="rounded-xl p-4" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);">
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm" style="color: #8a8580;">Transaction</span>
                            <span class="text-sm font-medium" style="color: #e8e4dc;">#{{ $selectedTransaction->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm" style="color: #8a8580;">Cashier</span>
                            <span class="text-sm font-medium" style="color: #e8e4dc;">{{ $selectedTransaction->cashier->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm" style="color: #8a8580;">Total</span>
                            <span class="text-sm font-semibold" style="color: #e8e4dc;">₱{{ number_format($selectedTransaction->total_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm" style="color: #8a8580;">Date</span>
                            <span class="text-sm font-medium" style="color: #e8e4dc;">{{ $selectedTransaction->created_at->format('M d, Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Reason --}}
                <div class="space-y-2">
                    <label class="text-sm font-medium" style="color: #c0bbb0;">Reason / Note</label>
                    <textarea
                        wire:model="reversalNote"
                        rows="3"
                        class="input-field w-full"
                        placeholder="Enter reason for void..."
                    ></textarea>
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-3 pt-4" style="border-top: 1px solid rgba(255,255,255,0.06);">
                    <button type="button" wire:click="closeModals" class="btn-secondary">
                        Cancel
                    </button>
                    <button type="submit" class="btn-warning">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Void Transaction
                    </button>
                </div>
            </form>
        </div>
    @endif

    {{-- Refund Modal --}}
    @if ($showRefundModal && $selectedTransaction)
        <div class="dialog-overlay" wire:click="closeModals"></div>
        <div class="dialog-content max-w-md">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold" style="color: #e8e4dc;">Refund Transaction</h3>
                <button wire:click="closeModals" class="btn-icon-sm" style="color: #6a6560;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="processRefund" class="space-y-4">
                {{-- Transaction Details --}}
                <div class="rounded-xl p-4" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);">
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm" style="color: #8a8580;">Transaction</span>
                            <span class="text-sm font-medium" style="color: #e8e4dc;">#{{ $selectedTransaction->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm" style="color: #8a8580;">Cashier</span>
                            <span class="text-sm font-medium" style="color: #e8e4dc;">{{ $selectedTransaction->cashier->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm" style="color: #8a8580;">Total</span>
                            <span class="text-sm font-semibold" style="color: #e8e4dc;">₱{{ number_format($selectedTransaction->total_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm" style="color: #8a8580;">Date</span>
                            <span class="text-sm font-medium" style="color: #e8e4dc;">{{ $selectedTransaction->created_at->format('M d, Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Reason --}}
                <div class="space-y-2">
                    <label class="text-sm font-medium" style="color: #c0bbb0;">Reason / Note</label>
                    <textarea
                        wire:model="reversalNote"
                        rows="3"
                        class="input-field w-full"
                        placeholder="Enter reason for refund..."
                    ></textarea>
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-3 pt-4" style="border-top: 1px solid rgba(255,255,255,0.06);">
                    <button type="button" wire:click="closeModals" class="btn-secondary">
                        Cancel
                    </button>
                    <button type="submit" class="btn-destructive">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Refund Transaction
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>
