<x-app-layout>
    <div class="p-4 sm:p-6 space-y-5">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                @php
                    $hour = now()->hour;
                    $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
                @endphp
                <h1 class="text-lg font-semibold" style="color: hsl(var(--foreground));">{{ $greeting }}, {{ auth()->user()->name }}</h1>
                <p class="text-xs mt-0.5" style="color: hsl(var(--muted-foreground));">{{ now()->translatedFormat('l, F j, Y') }}</p>
            </div>
            <span class="text-[11px] font-medium px-2 py-0.5 rounded-md" style="background: rgba(200,155,60,0.1); color: hsl(var(--gold-text));">{{ ucfirst(auth()->user()->role) }}</span>
        </div>

        @if (auth()->user()->isAdmin())
            {{-- Admin Stats --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                <a href="{{ route('admin.transactions') }}" wire:navigate class="dash-card-link">
                    <div class="flex items-center gap-3">
                        <div class="dash-icon" style="background: rgba(200,155,60,0.1);">
                            <svg class="w-4 h-4" style="color: hsl(var(--gold-text));" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="dash-stat-label">Today's Sales</p>
                            <p class="dash-stat-value">&#8369;{{ number_format($todaySales, 2) }}</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.transactions') }}" wire:navigate class="dash-card-link">
                    <div class="flex items-center gap-3">
                        <div class="dash-icon" style="background: rgba(200,155,60,0.08);">
                            <svg class="w-4 h-4" style="color: hsl(var(--gold-text));" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                            </svg>
                        </div>
                        <div>
                            <p class="dash-stat-label">Transactions</p>
                            <p class="dash-stat-value">{{ $todayTransactions }}</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.products') }}" wire:navigate class="dash-card-link">
                    <div class="flex items-center gap-3">
                        <div class="dash-icon" style="background: rgba(200,155,60,0.08);">
                            <svg class="w-4 h-4" style="color: hsl(var(--gold-text));" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                            </svg>
                        </div>
                        <div>
                            <p class="dash-stat-label">Products</p>
                            <p class="dash-stat-value">{{ $totalProducts }}</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.discounts') }}" wire:navigate class="dash-card-link">
                    <div class="flex items-center gap-3">
                        <div class="dash-icon" style="background: rgba(200,155,60,0.08);">
                            <svg class="w-4 h-4" style="color: hsl(var(--gold-text));" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                            </svg>
                        </div>
                        <div>
                            <p class="dash-stat-label">Discounts</p>
                            <p class="dash-stat-value">{{ $activeDiscounts }}</p>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Quick Actions --}}
            <div>
                <h2 class="dash-section-title">Quick Actions</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                    <a href="{{ route('admin.products') }}" wire:navigate class="dash-card-link flex items-center gap-3">
                        <div class="dash-icon" style="background: rgba(200,155,60,0.1);">
                            <svg class="w-4 h-4" style="color: hsl(var(--gold-text));" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[13px] font-medium" style="color: hsl(var(--foreground));">Products</p>
                            <p class="text-[11px]" style="color: hsl(var(--muted-foreground));">Manage inventory</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.discounts') }}" wire:navigate class="dash-card-link flex items-center gap-3">
                        <div class="dash-icon" style="background: rgba(200,155,60,0.08);">
                            <svg class="w-4 h-4" style="color: hsl(var(--gold-text));" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[13px] font-medium" style="color: hsl(var(--foreground));">Discounts</p>
                            <p class="text-[11px]" style="color: hsl(var(--muted-foreground));">Configure promotions</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.reports') }}" wire:navigate class="dash-card-link flex items-center gap-3">
                        <div class="dash-icon" style="background: rgba(200,155,60,0.08);">
                            <svg class="w-4 h-4" style="color: hsl(var(--gold-text));" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[13px] font-medium" style="color: hsl(var(--foreground));">Reports</p>
                            <p class="text-[11px]" style="color: hsl(var(--muted-foreground));">Sales & commissions</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.users') }}" wire:navigate class="dash-card-link flex items-center gap-3">
                        <div class="dash-icon" style="background: rgba(200,155,60,0.08);">
                            <svg class="w-4 h-4" style="color: hsl(var(--gold-text));" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[13px] font-medium" style="color: hsl(var(--foreground));">Users</p>
                            <p class="text-[11px]" style="color: hsl(var(--muted-foreground));">Manage staff</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.transactions') }}" wire:navigate class="dash-card-link flex items-center gap-3">
                        <div class="dash-icon" style="background: hsl(var(--muted));">
                            <svg class="w-4 h-4" style="color: hsl(var(--muted-foreground));" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[13px] font-medium" style="color: hsl(var(--foreground));">Transactions</p>
                            <p class="text-[11px]" style="color: hsl(var(--muted-foreground));">View all history</p>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Stock Alerts --}}
            @if ($lowStockVariants > 0 || $outOfStockVariants > 0)
                <a href="{{ route('admin.products') }}" wire:navigate
                   class="block rounded-xl p-3 transition-colors duration-150"
                   @if ($outOfStockVariants > 0)
                       style="background: rgba(180,50,50,0.06); border: 1px solid rgba(180,50,50,0.12);"
                   @else
                       style="background: rgba(200,155,60,0.06); border: 1px solid rgba(200,155,60,0.12);"
                   @endif>
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 shrink-0"
                            @if ($outOfStockVariants > 0) style="color: hsl(var(--danger-text));" @else style="color: hsl(var(--gold-text));" @endif
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                        <div class="flex-1 min-w-0">
                            <span class="text-[13px] font-medium"
                                @if ($outOfStockVariants > 0) style="color: hsl(var(--danger-text));" @else style="color: hsl(var(--gold-text));" @endif>
                                Stock Alerts
                            </span>
                            <span class="text-[13px] ml-2"
                                @if ($outOfStockVariants > 0) style="color: hsl(var(--danger-text));" @else style="color: hsl(var(--gold-text));" @endif>
                                @if ($outOfStockVariants > 0)
                                    {{ $outOfStockVariants }} out of stock
                                @endif
                                @if ($outOfStockVariants > 0 && $lowStockVariants > 0) &middot; @endif
                                @if ($lowStockVariants > 0)
                                    {{ $lowStockVariants }} low stock
                                @endif
                            </span>
                        </div>
                        <svg class="w-4 h-4 shrink-0"
                            @if ($outOfStockVariants > 0) style="color: hsl(var(--danger-text));" @else style="color: hsl(var(--gold-text));" @endif
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </div>
                </a>
            @endif

            {{-- Recent Transactions --}}
            @if ($recentTransactions->count() > 0)
                <div class="dash-card p-0 overflow-hidden">
                    <div class="px-4 py-3 flex items-center justify-between" style="border-bottom: 1px solid hsl(var(--border));">
                        <h2 class="text-[13px] font-semibold" style="color: hsl(var(--foreground));">Recent Transactions</h2>
                        <a href="{{ route('admin.transactions') }}" wire:navigate class="text-[11px] font-medium transition-colors duration-150" style="color: hsl(var(--gold-text));">View all &rarr;</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full dash-table">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Cashier</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentTransactions as $txn)
                                    <tr>
                                        <td style="color: hsl(var(--muted-foreground));">{{ $txn->created_at->format('g:i A') }}</td>
                                        <td class="font-medium" style="color: hsl(var(--foreground));">{{ $txn->cashier->name }}</td>
                                        <td class="font-medium" style="color: hsl(var(--foreground));">&#8369;{{ number_format($txn->total_amount, 2) }}</td>
                                        <td>
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[11px] font-medium"
                                                @if ($txn->status === 'completed')
                                                    style="background: rgba(34,139,80,0.12); color: hsl(var(--success-text));"
                                                @elseif ($txn->status === 'voided')
                                                    style="background: rgba(180,50,50,0.12); color: hsl(var(--danger-text));"
                                                @else
                                                    style="background: rgba(200,155,60,0.1); color: hsl(var(--gold-text));"
                                                @endif>
                                                {{ ucfirst($txn->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        @else
            {{-- Cashier Stats --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                <a href="{{ route('cashier.transactions') }}" wire:navigate class="dash-card-link">
                    <div class="flex items-center gap-3">
                        <div class="dash-icon" style="background: rgba(200,155,60,0.1);">
                            <svg class="w-4 h-4" style="color: hsl(var(--gold-text));" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="dash-stat-label">Today's Sales</p>
                            <p class="dash-stat-value">&#8369;{{ number_format($todaySales, 2) }}</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('cashier.transactions') }}" wire:navigate class="dash-card-link">
                    <div class="flex items-center gap-3">
                        <div class="dash-icon" style="background: rgba(200,155,60,0.08);">
                            <svg class="w-4 h-4" style="color: hsl(var(--gold-text));" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                            </svg>
                        </div>
                        <div>
                            <p class="dash-stat-label">Transactions</p>
                            <p class="dash-stat-value">{{ $todayTransactions }}</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('cashier.transactions') }}" wire:navigate class="dash-card-link">
                    <div class="flex items-center gap-3">
                        <div class="dash-icon" style="background: rgba(200,155,60,0.08);">
                            <svg class="w-4 h-4" style="color: hsl(var(--gold-text));" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="dash-stat-label">Items Sold</p>
                            <p class="dash-stat-value">{{ $todayItems }}</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('cashier.transactions') }}" wire:navigate class="dash-card-link">
                    <div class="flex items-center gap-3">
                        <div class="dash-icon" style="background: rgba(200,155,60,0.08);">
                            <svg class="w-4 h-4" style="color: hsl(var(--gold-text));" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                            </svg>
                        </div>
                        <div>
                            <p class="dash-stat-label">My Commission</p>
                            <p class="dash-stat-value">&#8369;{{ number_format($todayCommission, 2) }}</p>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Cashier Quick Actions --}}
            <div>
                <h2 class="dash-section-title">Quick Actions</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <a href="{{ route('cashier.checkout') }}" wire:navigate class="dash-card-link flex items-center gap-3">
                        <div class="dash-icon" style="background: rgba(200,155,60,0.1);">
                            <svg class="w-4 h-4" style="color: hsl(var(--gold-text));" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121 0 2.09-.773 2.34-1.872l1.836-8.046A1.125 1.125 0 0018.054 3H5.106m2.394 11.25l-1.5-6h13.5" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[13px] font-medium" style="color: hsl(var(--foreground));">Checkout</p>
                            <p class="text-[11px]" style="color: hsl(var(--muted-foreground));">Process a sale</p>
                        </div>
                    </a>

                    <a href="{{ route('cashier.transactions') }}" wire:navigate class="dash-card-link flex items-center gap-3">
                        <div class="dash-icon" style="background: rgba(200,155,60,0.08);">
                            <svg class="w-4 h-4" style="color: hsl(var(--gold-text));" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[13px] font-medium" style="color: hsl(var(--foreground));">Transactions</p>
                            <p class="text-[11px]" style="color: hsl(var(--muted-foreground));">View today's sales</p>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Recent Transactions --}}
            @if ($recentTransactions->count() > 0)
                <div class="dash-card p-0 overflow-hidden">
                    <div class="px-4 py-3 flex items-center justify-between" style="border-bottom: 1px solid hsl(var(--border));">
                        <h2 class="text-[13px] font-semibold" style="color: hsl(var(--foreground));">Recent Transactions</h2>
                        <a href="{{ route('cashier.transactions') }}" wire:navigate class="text-[11px] font-medium transition-colors duration-150" style="color: hsl(var(--gold-text));">View all &rarr;</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full dash-table">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Items</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentTransactions as $txn)
                                    <tr>
                                        <td style="color: hsl(var(--muted-foreground));">{{ $txn->created_at->format('g:i A') }}</td>
                                        <td style="color: hsl(var(--muted-foreground));">{{ $txn->lineItems->sum('quantity') }}</td>
                                        <td class="font-medium" style="color: hsl(var(--foreground));">&#8369;{{ number_format($txn->total_amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        @endif
    </div>
</x-app-layout>
