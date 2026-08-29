<div class="p-6">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-2xl font-bold text-gray-900">Transaction History</h1>
        <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
            <div class="flex items-center gap-2">
                <label class="text-sm text-gray-700">From:</label>
                <input type="date" wire:model="dateFrom" class="input-field w-40">
            </div>
            <div class="flex items-center gap-2">
                <label class="text-sm text-gray-700">To:</label>
                <input type="date" wire:model="dateTo" class="input-field w-40">
            </div>
            <div class="flex items-center gap-2">
                <label class="text-sm text-gray-700">Status:</label>
                <select wire:model="statusFilter" class="input-field w-36">
                    <option value="all">All</option>
                    <option value="completed">Completed</option>
                    <option value="voided">Voided</option>
                    <option value="refunded">Refunded</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search..."
                    class="input-field w-64"
                >
            </div>
        </div>
    </div>

    @if ($transactions->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cashier</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($transactions as $transaction)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">#{{ $transaction->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $transaction->cashier->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $transaction->lineItems->sum('quantity') }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">₱{{ number_format($transaction->total_amount, 2) }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->getStatusBadgeClass($transaction->status) }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                @php
                                    $canVoid = $transaction->isCompleted() && $transaction->created_at->isToday();
                                    $canRefund = $transaction->isCompleted();
                                @endphp
                                @if ($canVoid)
                                    <button wire:click="openVoidModal({{ $transaction->id }})" class="text-yellow-600 hover:text-yellow-900 mr-3">Void</button>
                                @endif
                                @if ($canRefund)
                                    <button wire:click="openRefundModal({{ $transaction->id }})" class="text-red-600 hover:text-red-900">Refund</button>
                                @endif
                                @if ($transaction->status !== 'completed')
                                    <span class="text-gray-400 text-xs ml-2">(Reversed)</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No transactions found</h3>
        </div>
    @endif

    @if ($showVoidModal && $selectedTransaction)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModals"></div>
                <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full">
                    <div class="flex items-center justify-between p-4 border-b">
                        <h3 class="text-lg font-medium text-gray-900">Void Transaction #{{ $selectedTransaction->id }}</h3>
                        <button wire:click="closeModals" class="text-gray-400 hover:text-gray-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <form wire:submit.prevent="processVoid" class="p-4 space-y-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">
                                <strong>Transaction:</strong> #{{ $selectedTransaction->id }}<br>
                                <strong>Cashier:</strong> {{ $selectedTransaction->cashier->name }}<br>
                                <strong>Total:</strong> ₱{{ number_format($selectedTransaction->total_amount, 2) }}<br>
                                <strong>Date:</strong> {{ $selectedTransaction->created_at->format('M d, Y H:i') }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reason / Note</label>
                            <textarea wire:model="reversalNote" rows="3" class="input-field w-full" placeholder="Enter reason for void..."></textarea>
                        </div>
                        <div class="flex justify-end space-x-3 border-t pt-4">
                            <button type="button" wire:click="closeModals" class="btn-secondary">Cancel</button>
                            <button type="submit" class="btn-yellow">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Void Transaction
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    @if ($showRefundModal && $selectedTransaction)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModals"></div>
                <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full">
                    <div class="flex items-center justify-between p-4 border-b">
                        <h3 class="text-lg font-medium text-gray-900">Refund Transaction #{{ $selectedTransaction->id }}</h3>
                        <button wire:click="closeModals" class="text-gray-400 hover:text-gray-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <form wire:submit.prevent="processRefund" class="p-4 space-y-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">
                                <strong>Transaction:</strong> #{{ $selectedTransaction->id }}<br>
                                <strong>Cashier:</strong> {{ $selectedTransaction->cashier->name }}<br>
                                <strong>Total:</strong> ₱{{ number_format($selectedTransaction->total_amount, 2) }}<br>
                                <strong>Date:</strong> {{ $selectedTransaction->created_at->format('M d, Y H:i') }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reason / Note</label>
                            <textarea wire:model="reversalNote" rows="3" class="input-field w-full" placeholder="Enter reason for refund..."></textarea>
                        </div>
                        <div class="flex justify-end space-x-3 border-t pt-4">
                            <button type="button" wire:click="closeModals" class="btn-secondary">Cancel</button>
                            <button type="submit" class="btn-red">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Refund Transaction
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>