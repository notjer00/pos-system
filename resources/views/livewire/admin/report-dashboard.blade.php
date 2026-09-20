<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-lg font-semibold" style="color: #e0dcd4;">Reports Dashboard</h1>
            <p class="text-sm mt-0.5" style="color: #706860;">Sales analytics and employee performance</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2">
                <label class="text-xs font-medium" style="color: #706860;">From:</label>
                <input type="date" wire:model="dateFrom" class="input-field w-40">
            </div>
            <div class="flex items-center gap-2">
                <label class="text-xs font-medium" style="color: #706860;">To:</label>
                <input type="date" wire:model="dateTo" class="input-field w-40">
            </div>
            <div class="flex items-center gap-2">
                <span class="rpt-export-label">CSV</span>
                <button wire:click="exportReport" wire:loading.attr="disabled" wire:target="exportReport" class="btn-secondary">
                    <svg wire:loading.remove wire:target="exportReport" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <svg wire:loading wire:target="exportReport" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span wire:loading wire:target="exportReport">Generating...</span>
                    <span wire:loading.remove wire:target="exportReport">Export CSV</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Summary Stat Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="rpt-stat-card">
            <div class="flex items-center gap-3 mb-3">
                <div class="rpt-stat-icon" style="background: rgba(200,155,60,0.1);">
                    <svg class="w-5 h-5" style="color: #d4a850;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-xs font-medium" style="color: #706860;">Total Sales</p>
            </div>
            <p class="text-3xl font-bold" style="color: #d4b060;">&#8369;{{ number_format($salesSummary['total_sales'], 2) }}</p>
        </div>

        <div class="rpt-stat-card">
            <div class="flex items-center gap-3 mb-3">
                <div class="rpt-stat-icon" style="background: rgba(34,139,80,0.1);">
                    <svg class="w-5 h-5" style="color: #5cc980;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <p class="text-xs font-medium" style="color: #706860;">Transactions</p>
            </div>
            <p class="text-3xl font-bold" style="color: #e0dcd4;">{{ $salesSummary['total_transactions'] }}</p>
        </div>

        <div class="rpt-stat-card">
            <div class="flex items-center gap-3 mb-3">
                <div class="rpt-stat-icon" style="background: rgba(200,155,60,0.1);">
                    <svg class="w-5 h-5" style="color: #d4b060;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <p class="text-xs font-medium" style="color: #706860;">Avg. Transaction</p>
            </div>
            <p class="text-3xl font-bold" style="color: #e0dcd4;">&#8369;{{ number_format($salesSummary['average_transaction'], 2) }}</p>
        </div>
    </div>

    {{-- Best Sellers + Commission --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        {{-- Top 10 Best Sellers --}}
        <div class="rpt-section">
            <div class="rpt-section-header">
                <h2 class="rpt-section-title">
                    <svg class="w-4 h-4" style="color: #d4a850;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    Top 10 Best Sellers (30 Days)
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full rpt-table">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Product</th>
                            <th>Variant</th>
                            <th>Units Sold</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bestSellers as $index => $seller)
                            <tr>
                                <td style="color: #706860;">{{ $index + 1 }}</td>
                                <td style="color: #e0dcd4;">{{ $seller['product']['name'] ?? 'N/A' }}</td>
                                <td style="color: #a09890;">{{ $seller['variant_name'] ?? $seller['size'] . '/' . $seller['color'] }}</td>
                                <td style="color: #d4a850;">{{ $seller['total_sold'] }}</td>
                            </tr>
                        @endforeach
                        @if (empty($bestSellers))
                            <tr>
                                <td colspan="4" class="rpt-empty">
                                    <svg class="mx-auto h-8 w-8" style="color: #5a5550;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    <p class="mt-2 text-sm" style="color: #706860;">No sales data for this period</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Commission Summary --}}
        <div class="rpt-section">
            <div class="rpt-section-header">
                <h2 class="rpt-section-title">
                    <svg class="w-4 h-4" style="color: #d4b060;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Commission Summary
                </h2>
            </div>
            <div class="p-5 space-y-4">
                <div class="rpt-commission-card">
                    <div class="flex items-center justify-between">
                        <span class="text-sm" style="color: #706860;">Total Commission</span>
                        <span class="text-xl font-bold" style="color: #d4b060;">&#8369;{{ number_format($commissionSummary['total_commission'], 2) }}</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full rpt-table">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Commission</th>
                                <th>Sales</th>
                                <th>Transactions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($commissionSummary['by_employee'] as $emp)
                                <tr>
                                    <td style="color: #e0dcd4;">{{ $emp['user']->name }}</td>
                                    <td style="color: #5cc980;">&#8369;{{ number_format($emp['total_commission'], 2) }}</td>
                                    <td style="color: #e0dcd4;">&#8369;{{ number_format($emp['total_sales'], 2) }}</td>
                                    <td style="color: #706860;">{{ $emp['transactions_count'] }}</td>
                                </tr>
                            @endforeach
                            @if (empty($commissionSummary['by_employee']))
                                <tr>
                                    <td colspan="4" class="rpt-empty">
                                        <svg class="mx-auto h-8 w-8" style="color: #5a5550;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        <p class="mt-2 text-sm" style="color: #706860;">No commission data for this period</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Low Stock Alerts --}}
    @if (!empty($lowStockVariants))
        <div class="rpt-section">
            <div class="rpt-section-header">
                <h2 class="rpt-section-title">
                    <svg class="w-4 h-4" style="color: #d4b060;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Low Stock Alerts
                </h2>
                <span class="badge-warning text-[11px]">{{ count($lowStockVariants) }} variants</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full rpt-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Variant</th>
                            <th>Current Stock</th>
                            <th>Threshold</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lowStockVariants as $variant)
                            <tr>
                                <td style="color: #e0dcd4;">{{ $variant['product']['name'] }}</td>
                                <td style="color: #a09890;">{{ $variant['variant_name'] ?? $variant['size'] . '/' . $variant['color'] }}</td>
                                <td style="color: #f0a0a0;">{{ $variant['current_stock'] }}</td>
                                <td style="color: #706860;">{{ $variant['low_stock_threshold'] }}</td>
                                <td>
                                    <span class="badge-warning text-[11px]">Low Stock</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
