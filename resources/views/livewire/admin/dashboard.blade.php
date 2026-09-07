<div class="p-6 space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-foreground">Dashboard</h1>
        <a href="{{ route('admin.reports') }}" class="btn-secondary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            Full Reports
        </a>
    </div>

    {{-- Quick Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <div class="card p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div>
                    <p class="text-xs text-muted-foreground">Total Products</p>
                    <p class="text-xl font-bold text-foreground">{{ $totalProducts }}</p>
                </div>
            </div>
        </div>

        <div class="card p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-success/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-muted-foreground">Active Discounts</p>
                    <p class="text-xl font-bold text-foreground">{{ $activeDiscounts }}</p>
                </div>
            </div>
        </div>

        <div class="card p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-warning/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <div>
                    <p class="text-xs text-muted-foreground">Today's Transactions</p>
                    <p class="text-xl font-bold text-foreground">{{ $todayTransactions }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Sales Overview --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="card p-5">
            <div class="flex items-center justify-between mb-1">
                <p class="text-sm text-muted-foreground">Today's Sales</p>
                <span class="badge badge-success text-xs">{{ $todayTransactions }} txns</span>
            </div>
            <p class="text-3xl font-bold text-foreground">₱{{ number_format($todaySales, 2) }}</p>
        </div>

        <div class="card p-5">
            <div class="flex items-center justify-between mb-1">
                <p class="text-sm text-muted-foreground">This Week</p>
                <span class="badge badge-primary text-xs">{{ $weekTransactions }} txns</span>
            </div>
            <p class="text-3xl font-bold text-foreground">₱{{ number_format($weekSales, 2) }}</p>
        </div>
    </div>

    {{-- 7-Day Sales Chart --}}
    <div class="card p-5">
        <h2 class="text-sm font-semibold text-foreground mb-4">Sales - Last 7 Days</h2>

        @if ($maxDailySale > 0)
            <div class="space-y-2">
                @foreach ($dailySales as $day)
                    @php
                        $barWidth = $maxDailySale > 0 ? round(($day['total'] / $maxDailySale) * 100) : 0;
                        $isToday = $day['date'] === now()->format('M d');
                    @endphp
                    <div class="flex items-center gap-3">
                        <div class="w-10 text-right">
                            <span class="text-xs {{ $isToday ? 'font-bold text-foreground' : 'text-muted-foreground' }}">{{ $day['day'] }}</span>
                        </div>
                        <div class="w-12 text-right">
                            <span class="text-xs {{ $isToday ? 'font-bold text-foreground' : 'text-muted-foreground' }}">{{ $day['date'] }}</span>
                        </div>
                        <div class="flex-1 h-6 bg-muted rounded overflow-hidden">
                            <div
                                class="h-full rounded transition-all duration-500 {{ $day['total'] > 0 ? 'bg-primary' : 'bg-muted' }}"
                                style="width: {{ max($barWidth, $day['total'] > 0 ? 2 : 0) }}%"
                            ></div>
                        </div>
                        <div class="w-24 text-right">
                            <span class="text-sm font-medium text-foreground">₱{{ number_format($day['total'], 2) }}</span>
                        </div>
                        <div class="w-8 text-right">
                            <span class="text-xs text-muted-foreground">{{ $day['count'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="flex items-center gap-4 mt-3 text-xs text-muted-foreground">
                <span>Bars: relative daily total</span>
                <span>Right number: transaction count</span>
            </div>
        @else
            <p class="text-sm text-muted-foreground py-4 text-center">No sales data for the past 7 days.</p>
        @endif
    </div>

    {{-- Low Stock + Commission --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        {{-- Low Stock Alerts --}}
        <div class="card overflow-hidden">
            <div class="px-4 py-3 border-b border-border flex items-center justify-between">
                <h2 class="text-sm font-semibold text-foreground flex items-center gap-2">
                    @if (count($lowStockVariants) > 0)
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-destructive opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-destructive"></span>
                        </span>
                    @endif
                    Low Stock Alerts
                </h2>
                @if (count($lowStockVariants) > 0)
                    <span class="badge badge-destructive">{{ count($lowStockVariants) }}</span>
                @endif
            </div>

            @if (count($lowStockVariants) > 0)
                <div class="divide-y divide-border max-h-64 overflow-y-auto">
                    @foreach ($lowStockVariants as $variant)
                        <div class="px-4 py-3 hover:bg-muted/30 transition-colors">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-foreground">{{ $variant['product']['name'] }}</p>
                                    <p class="text-xs text-muted-foreground">{{ $variant['size'] ?? $variant['footwear_size'] }} / {{ $variant['color'] }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-destructive">{{ $variant['current_stock'] }}</p>
                                    <p class="text-xs text-muted-foreground">/ {{ $variant['low_stock_threshold'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="px-4 py-8 text-center">
                    <svg class="mx-auto h-8 w-8 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <p class="mt-2 text-sm text-muted-foreground">All variants are above their thresholds.</p>
                </div>
            @endif
        </div>

        {{-- Commission Summary --}}
        <div class="card overflow-hidden">
            <div class="px-4 py-3 border-b border-border">
                <h2 class="text-sm font-semibold text-foreground">Commission Summary</h2>
            </div>

            <div class="p-4 space-y-4">
                {{-- Today --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs text-muted-foreground uppercase tracking-wider">Today</p>
                        <p class="text-lg font-bold text-foreground">₱{{ number_format($todayCommission['total_commission'], 2) }}</p>
                    </div>

                    @if ($todayCommission['by_employee']->isNotEmpty())
                        <div class="space-y-1">
                            @foreach ($todayCommission['by_employee'] as $emp)
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-muted-foreground">{{ $emp['user']->name ?? 'Unknown' }}</span>
                                    <span class="font-medium text-foreground">₱{{ number_format($emp['total_commission'], 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-muted-foreground">No commissions today.</p>
                    @endif
                </div>

                <div class="border-t border-border"></div>

                {{-- This Week --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs text-muted-foreground uppercase tracking-wider">This Week</p>
                        <p class="text-lg font-bold text-foreground">₱{{ number_format($weekCommission['total_commission'], 2) }}</p>
                    </div>

                    @if ($weekCommission['by_employee']->isNotEmpty())
                        <div class="space-y-1">
                            @foreach ($weekCommission['by_employee'] as $emp)
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-muted-foreground">{{ $emp['user']->name ?? 'Unknown' }}</span>
                                    <span class="font-medium text-foreground">
                                        ₱{{ number_format($emp['total_commission'], 2) }}
                                        <span class="text-xs text-muted-foreground">({{ $emp['transactions_count'] }} txns)</span>
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-muted-foreground">No commissions this week.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
