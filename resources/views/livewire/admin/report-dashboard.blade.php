<div class="p-6 space-y-6" x-data="{ downloadUrl: @entangle('downloadUrl') }" x-effect="if (downloadUrl) { window.location.href = downloadUrl; $wire.set('downloadUrl', null); }">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-2xl font-bold text-foreground">Reports Dashboard</h1>
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2">
                <label class="text-sm text-muted-foreground">From:</label>
                <input type="date" wire:model="dateFrom" class="input-field w-40">
            </div>
            <div class="flex items-center gap-2">
                <label class="text-sm text-muted-foreground">To:</label>
                <input type="date" wire:model="dateTo" class="input-field w-40">
            </div>
            <div class="flex items-center gap-2">
                <label class="text-sm text-muted-foreground">Export:</label>
                <select wire:model="exportFormat" class="input-field w-32">
                    <option value="pdf">PDF</option>
                    <option value="excel">Excel</option>
                </select>
                <button wire:click="exportReport" class="btn-secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Export
                </button>
            </div>
        </div>
    </div>

    {{-- Summary Stat Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="card p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-sm text-muted-foreground">Total Sales</p>
            </div>
            <p class="text-3xl font-bold text-warning">₱{{ number_format($salesSummary['total_sales'], 2) }}</p>
        </div>

        <div class="card p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-success/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <p class="text-sm text-muted-foreground">Transactions</p>
            </div>
            <p class="text-3xl font-bold text-foreground">{{ $salesSummary['total_transactions'] }}</p>
        </div>

        <div class="card p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-warning/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <p class="text-sm text-muted-foreground">Avg. Transaction</p>
            </div>
            <p class="text-3xl font-bold text-foreground">₱{{ number_format($salesSummary['average_transaction'], 2) }}</p>
        </div>
    </div>

    {{-- Best Sellers + Commission --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        {{-- Top 10 Best Sellers --}}
        <div class="card overflow-hidden">
            <div class="px-5 py-3.5 border-b border-border">
                <h2 class="text-sm font-semibold text-foreground flex items-center gap-2">
                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    Top 10 Best Sellers (30 Days)
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-border">
                    <thead>
                        <tr class="bg-muted/50">
                            <th class="px-5 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Rank</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Product</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Variant</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Units Sold</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @foreach ($bestSellers as $index => $seller)
                            <tr class="hover:bg-muted/30 transition-colors">
                                <td class="px-5 py-3.5 text-sm font-medium text-muted-foreground">{{ $index + 1 }}</td>
                                <td class="px-5 py-3.5 text-sm font-medium text-foreground">{{ $seller['product']['name'] ?? 'N/A' }}</td>
                                <td class="px-5 py-3.5 text-sm text-muted-foreground">{{ $seller['size'] }}/{{ $seller['color'] }}</td>
                                <td class="px-5 py-3.5 text-sm font-semibold text-primary">{{ $seller['total_sold'] }}</td>
                            </tr>
                        @endforeach
                        @if (empty($bestSellers))
                            <tr>
                                <td colspan="4" class="px-5 py-10 text-center">
                                    <svg class="mx-auto h-8 w-8 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    <p class="mt-2 text-sm text-muted-foreground">No sales data for this period</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Commission Summary --}}
        <div class="card overflow-hidden">
            <div class="px-5 py-3.5 border-b border-border">
                <h2 class="text-sm font-semibold text-foreground flex items-center gap-2">
                    <svg class="w-4 h-4 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Commission Summary
                </h2>
            </div>
            <div class="p-5 space-y-4">
                <div class="flex items-center justify-between p-3 rounded-lg bg-warning/5 border border-warning/10">
                    <span class="text-sm text-muted-foreground">Total Commission</span>
                    <span class="text-xl font-bold text-warning">₱{{ number_format($commissionSummary['total_commission'], 2) }}</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-border">
                        <thead>
                            <tr class="bg-muted/50">
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Employee</th>
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Commission</th>
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Sales</th>
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Transactions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @foreach ($commissionSummary['by_employee'] as $emp)
                                <tr class="hover:bg-muted/30 transition-colors">
                                    <td class="px-4 py-3 text-sm font-medium text-foreground">{{ $emp['user']->name }}</td>
                                    <td class="px-4 py-3 text-sm font-semibold text-success">₱{{ number_format($emp['total_commission'], 2) }}</td>
                                    <td class="px-4 py-3 text-sm text-foreground">₱{{ number_format($emp['total_sales'], 2) }}</td>
                                    <td class="px-4 py-3 text-sm text-muted-foreground">{{ $emp['transactions_count'] }}</td>
                                </tr>
                            @endforeach
                            @if (empty($commissionSummary['by_employee']))
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center">
                                        <svg class="mx-auto h-8 w-8 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        <p class="mt-2 text-sm text-muted-foreground">No commission data for this period</p>
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
        <div class="card overflow-hidden">
            <div class="px-5 py-3.5 border-b border-border flex items-center justify-between">
                <h2 class="text-sm font-semibold text-foreground flex items-center gap-2">
                    @if (count($lowStockVariants) > 0)
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-warning opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-warning"></span>
                        </span>
                    @endif
                    <svg class="w-4 h-4 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Low Stock Alerts
                </h2>
                <span class="badge badge-warning">{{ count($lowStockVariants) }} variants</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-border">
                    <thead>
                        <tr class="bg-muted/50">
                            <th class="px-5 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Product</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Variant</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Current Stock</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Threshold</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @foreach ($lowStockVariants as $variant)
                            <tr class="hover:bg-muted/30 transition-colors">
                                <td class="px-5 py-3.5 text-sm font-medium text-foreground">{{ $variant['product']['name'] }}</td>
                                <td class="px-5 py-3.5 text-sm text-muted-foreground">{{ $variant['size'] }}/{{ $variant['color'] }}</td>
                                <td class="px-5 py-3.5 text-sm font-semibold text-destructive">{{ $variant['current_stock'] }}</td>
                                <td class="px-5 py-3.5 text-sm text-muted-foreground">{{ $variant['low_stock_threshold'] }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="badge badge-warning">Low Stock</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
