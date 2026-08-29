<div class="p-6">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-2xl font-bold text-gray-900">Reports Dashboard</h1>
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2">
                <label class="text-sm text-gray-700">From:</label>
                <input type="date" wire:model="dateFrom" class="input-field w-40">
            </div>
            <div class="flex items-center gap-2">
                <label class="text-sm text-gray-700">To:</label>
                <input type="date" wire:model="dateTo" class="input-field w-40">
            </div>
            <div class="flex items-center gap-2">
                <label class="text-sm text-gray-700">Export:</label>
                <select wire:model="exportFormat" class="input-field w-32">
                    <option value="pdf">PDF</option>
                    <option value="excel">Excel</option>
                </select>
                <button wire:click="exportReport" class="btn-secondary">Export</button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm font-medium text-gray-500 mb-1">Total Sales</h3>
            <p class="text-3xl font-bold text-gray-900">₱{{ number_format($salesSummary['total_sales'], 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm font-medium text-gray-500 mb-1">Transactions</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $salesSummary['total_transactions'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm font-medium text-gray-500 mb-1">Avg. Transaction</h3>
            <p class="text-3xl font-bold text-gray-900">₱{{ number_format($salesSummary['average_transaction'], 2) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-medium text-gray-900">Top 10 Best Sellers (30 Days)</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Variant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Units Sold</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($bestSellers as $index => $seller)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $seller['product']['name'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $seller['size'] }}/{{ $seller['color'] }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-indigo-600">{{ $seller['total_sold'] }}</td>
                            </tr>
                        @endforeach
                        @if (empty($bestSellers))
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No sales data for this period</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-medium text-gray-900">Commission Summary</h3>
            </div>
            <div class="p-6">
                <p class="text-2xl font-bold text-gray-900 mb-6">Total Commission: ₱{{ number_format($commissionSummary['total_commission'], 2) }}</p>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commission</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sales</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transactions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($commissionSummary['by_employee'] as $emp)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $emp['user']->name }}</td>
                                    <td class="px-4 py-2 text-sm font-medium text-green-600">₱{{ number_format($emp['total_commission'], 2) }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-900">₱{{ number_format($emp['total_sales'], 2) }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $emp['transactions_count'] }}</td>
                                </tr>
                            @endforeach
                            @if (empty($commissionSummary['by_employee']))
                                <tr>
                                    <td colspan="4" class="px-4 py-2 text-center text-sm text-gray-500">No commission data for this period</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if (!empty($lowStockVariants))
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b bg-red-50">
                <h3 class="text-lg font-medium text-red-900 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    Low Stock Alerts ({{ count($lowStockVariants) }} variants)
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Variant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Threshold</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($lowStockVariants as $variant)
                            <tr class="hover:bg-red-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $variant['product']['name'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $variant['size'] }}/{{ $variant['color'] }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-red-600">{{ $variant['current_stock'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $variant['low_stock_threshold'] }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Low Stock
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>