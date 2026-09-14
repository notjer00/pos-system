<x-app-layout>
    <div class="p-4 sm:p-6 space-y-6">
        {{-- Welcome Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold" style="color: #e8e4dc;">
                    @php
                        $hour = now()->hour;
                        $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
                    @endphp
                    {{ $greeting }}, {{ auth()->user()->name }}
                </h1>
                <p class="text-sm mt-1" style="color: #6a6560;">
                    {{ now()->translatedFormat('l, F j, Y') }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium"
                      @if (auth()->user()->isAdmin())
                          style="background: rgba(45,133,112,0.12); color: #5cbfaa; border: 1px solid rgba(45,133,112,0.15);"
                      @else
                          style="background: rgba(61,139,139,0.1); color: #5aa8a8; border: 1px solid rgba(61,139,139,0.1);"
                      @endif>
                    {{ ucfirst(auth()->user()->role) }}
                </span>
            </div>
        </div>

        @if (auth()->user()->isAdmin())
            {{-- Admin Stats --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('admin.transactions') }}" wire:navigate class="stat-card rounded-2xl p-5 block transition-all duration-300"
                   style="background: rgba(255,255,255,0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.06);"
                   onmouseover="this.style.borderColor='rgba(45,133,112,0.2)'; this.style.boxShadow='0 4px 24px rgba(45,133,112,0.08)';"
                   onmouseout="this.style.borderColor='rgba(255,255,255,0.06)'; this.style.boxShadow='none';">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium" style="color: #6a6560;">Today's Sales</p>
                            <p class="text-2xl font-bold mt-1" style="color: #e8e4dc;">₱{{ number_format($todaySales, 2) }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(45,133,112,0.12); border: 1px solid rgba(45,133,112,0.1);">
                            <svg class="w-5 h-5" style="color: #5cbfaa;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.transactions') }}" wire:navigate class="stat-card rounded-2xl p-5 block transition-all duration-300"
                   style="background: rgba(255,255,255,0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.06);"
                   onmouseover="this.style.borderColor='rgba(200,155,60,0.2)'; this.style.boxShadow='0 4px 24px rgba(200,155,60,0.06)';"
                   onmouseout="this.style.borderColor='rgba(255,255,255,0.06)'; this.style.boxShadow='none';">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium" style="color: #6a6560;">Transactions</p>
                            <p class="text-2xl font-bold mt-1" style="color: #e8e4dc;">{{ $todayTransactions }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(200,155,60,0.1); border: 1px solid rgba(200,155,60,0.1);">
                            <svg class="w-5 h-5" style="color: #d4b060;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                            </svg>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.products') }}" wire:navigate class="stat-card rounded-2xl p-5 block transition-all duration-300"
                   style="background: rgba(255,255,255,0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.06);"
                   onmouseover="this.style.borderColor='rgba(61,139,139,0.2)'; this.style.boxShadow='0 4px 24px rgba(61,139,139,0.06)';"
                   onmouseout="this.style.borderColor='rgba(255,255,255,0.06)'; this.style.boxShadow='none';">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium" style="color: #6a6560;">Active Products</p>
                            <p class="text-2xl font-bold mt-1" style="color: #e8e4dc;">{{ $totalProducts }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(61,139,139,0.1); border: 1px solid rgba(61,139,139,0.1);">
                            <svg class="w-5 h-5" style="color: #5aa8a8;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                            </svg>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.discounts') }}" wire:navigate class="stat-card rounded-2xl p-5 block transition-all duration-300"
                   style="background: rgba(255,255,255,0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.06);"
                   onmouseover="this.style.borderColor='rgba(200,155,60,0.15)'; this.style.boxShadow='0 4px 24px rgba(200,155,60,0.04)';"
                   onmouseout="this.style.borderColor='rgba(255,255,255,0.06)'; this.style.boxShadow='none';">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium" style="color: #6a6560;">Active Discounts</p>
                            <p class="text-2xl font-bold mt-1" style="color: #e8e4dc;">{{ $activeDiscounts }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(200,155,60,0.1); border: 1px solid rgba(200,155,60,0.08);">
                            <svg class="w-5 h-5" style="color: #d4b060;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                            </svg>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Quick Actions --}}
            <div>
                <h2 class="text-xs font-semibold uppercase tracking-widest mb-3" style="color: #6a6560;">Quick Actions</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <a href="{{ route('admin.products') }}" wire:navigate class="group flex items-center gap-4 rounded-2xl p-4 transition-all duration-300"
                       style="background: rgba(255,255,255,0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.06);"
                       onmouseover="this.style.borderColor='rgba(45,133,112,0.2)'; this.style.boxShadow='0 4px 24px rgba(45,133,112,0.06)';"
                       onmouseout="this.style.borderColor='rgba(255,255,255,0.06)'; this.style.boxShadow='none';">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-200" style="background: rgba(45,133,112,0.1); border: 1px solid rgba(45,133,112,0.08);">
                            <svg class="w-5 h-5" style="color: #5cbfaa;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold" style="color: #e8e4dc;">Products</p>
                            <p class="text-xs" style="color: #6a6560;">Manage inventory</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.discounts') }}" wire:navigate class="group flex items-center gap-4 rounded-2xl p-4 transition-all duration-300"
                       style="background: rgba(255,255,255,0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.06);"
                       onmouseover="this.style.borderColor='rgba(200,155,60,0.2)'; this.style.boxShadow='0 4px 24px rgba(200,155,60,0.04)';"
                       onmouseout="this.style.borderColor='rgba(255,255,255,0.06)'; this.style.boxShadow='none';">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-200" style="background: rgba(200,155,60,0.08); border: 1px solid rgba(200,155,60,0.08);">
                            <svg class="w-5 h-5" style="color: #d4b060;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold" style="color: #e8e4dc;">Discounts</p>
                            <p class="text-xs" style="color: #6a6560;">Configure promotions</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.reports') }}" wire:navigate class="group flex items-center gap-4 rounded-2xl p-4 transition-all duration-300"
                       style="background: rgba(255,255,255,0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.06);"
                       onmouseover="this.style.borderColor='rgba(61,139,139,0.2)'; this.style.boxShadow='0 4px 24px rgba(61,139,139,0.04)';"
                       onmouseout="this.style.borderColor='rgba(255,255,255,0.06)'; this.style.boxShadow='none';">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-200" style="background: rgba(61,139,139,0.08); border: 1px solid rgba(61,139,139,0.08);">
                            <svg class="w-5 h-5" style="color: #5aa8a8;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold" style="color: #e8e4dc;">Reports</p>
                            <p class="text-xs" style="color: #6a6560;">Sales & commissions</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.users') }}" wire:navigate class="group flex items-center gap-4 rounded-2xl p-4 transition-all duration-300"
                       style="background: rgba(255,255,255,0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.06);"
                       onmouseover="this.style.borderColor='rgba(45,133,112,0.2)'; this.style.boxShadow='0 4px 24px rgba(45,133,112,0.06)';"
                       onmouseout="this.style.borderColor='rgba(255,255,255,0.06)'; this.style.boxShadow='none';">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-200" style="background: rgba(45,133,112,0.1); border: 1px solid rgba(45,133,112,0.08);">
                            <svg class="w-5 h-5" style="color: #5cbfaa;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold" style="color: #e8e4dc;">Users</p>
                            <p class="text-xs" style="color: #6a6560;">Manage staff accounts</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.transactions') }}" wire:navigate class="group flex items-center gap-4 rounded-2xl p-4 transition-all duration-300"
                       style="background: rgba(255,255,255,0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.06);"
                       onmouseover="this.style.borderColor='rgba(255,255,255,0.1)'; this.style.boxShadow='0 4px 24px rgba(0,0,0,0.1)';"
                       onmouseout="this.style.borderColor='rgba(255,255,255,0.06)'; this.style.boxShadow='none';">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-200" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06);">
                            <svg class="w-5 h-5" style="color: #8a8580;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold" style="color: #e8e4dc;">Transactions</p>
                            <p class="text-xs" style="color: #6a6560;">View all sales history</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.inbox') }}" wire:navigate class="group flex items-center gap-4 rounded-2xl p-4 transition-all duration-300"
                       style="background: rgba(255,255,255,0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.06);"
                       onmouseover="this.style.borderColor='rgba(200,155,60,0.15)'; this.style.boxShadow='0 4px 24px rgba(200,155,60,0.04)';"
                       onmouseout="this.style.borderColor='rgba(255,255,255,0.06)'; this.style.boxShadow='none';">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-200 relative" style="background: rgba(200,155,60,0.08); border: 1px solid rgba(200,155,60,0.08);">
                            <svg class="w-5 h-5" style="color: #d4b060;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                            @if (auth()->user()->unreadMessagesCount() > 0)
                                <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full flex items-center justify-center text-[9px] font-bold"
                                      style="background: #c89b3c; color: #0d0f10;">
                                    {{ auth()->user()->unreadMessagesCount() }}
                                </span>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-semibold" style="color: #e8e4dc;">Messages</p>
                            <p class="text-xs" style="color: #6a6560;">Communicate with team</p>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Alerts --}}
            @if ($lowStockVariants > 0 || $outOfStockVariants > 0)
                <a href="{{ route('admin.products') }}" wire:navigate class="block rounded-2xl p-4 transition-all duration-300"
                   @if ($outOfStockVariants > 0)
                       style="background: rgba(180,50,50,0.06); border: 1px solid rgba(180,50,50,0.12);"
                   @else
                       style="background: rgba(200,155,60,0.06); border: 1px solid rgba(200,155,60,0.12);"
                   @endif
                   onmouseover="this.style.boxShadow='0 4px 24px rgba(0,0,0,0.1)';"
                   onmouseout="this.style.boxShadow='none';">
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 mt-0.5"
                            @if ($outOfStockVariants > 0) style="color: #f0a0a0;" @else style="color: #d4b060;" @endif>
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold"
                                @if ($outOfStockVariants > 0) style="color: #f0a0a0;" @else style="color: #d4b060;" @endif>
                                Stock Alerts
                            </p>
                            <p class="text-sm mt-1"
                                @if ($outOfStockVariants > 0) style="color: #d08080;" @else style="color: #b09040;" @endif>
                                @if ($outOfStockVariants > 0)
                                    {{ $outOfStockVariants }} variant{{ $outOfStockVariants > 1 ? 's' : '' }} out of stock
                                @endif
                                @if ($outOfStockVariants > 0 && $lowStockVariants > 0) &middot; @endif
                                @if ($lowStockVariants > 0)
                                    {{ $lowStockVariants }} variant{{ $lowStockVariants > 1 ? 's' : '' }} low on stock
                                @endif
                            </p>
                        </div>
                        <svg class="w-5 h-5 mt-0.5 shrink-0"
                            @if ($outOfStockVariants > 0) style="color: #f0a0a0;" @else style="color: #d4b060;" @endif fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </div>
                </a>
            @endif

            {{-- Recent Transactions --}}
            @if ($recentTransactions->count() > 0)
                <div class="rounded-2xl overflow-hidden"
                     style="background: rgba(255,255,255,0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.06);">
                    <div class="px-5 py-4 flex items-center justify-between" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                        <h2 class="text-sm font-semibold" style="color: #e8e4dc;">Recent Transactions</h2>
                        <a href="{{ route('admin.transactions') }}" wire:navigate class="text-xs font-medium transition-all duration-200" style="color: #5cbfaa;" onmouseover="this.style.color='#7ed4bf';" onmouseout="this.style.color='#5cbfaa';">View all &rarr;</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: #6a6560;">Time</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: #6a6560;">Cashier</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: #6a6560;">Amount</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: #6a6560;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentTransactions as $txn)
                                    <tr class="transition-all duration-200" style="border-bottom: 1px solid rgba(255,255,255,0.03);"
                                        onmouseover="this.style.background='rgba(255,255,255,0.02)';"
                                        onmouseout="this.style.background='';">
                                        <td class="px-5 py-3 text-sm" style="color: #6a6560;">{{ $txn->created_at->format('g:i A') }}</td>
                                        <td class="px-5 py-3 text-sm font-medium" style="color: #e8e4dc;">{{ $txn->cashier->name }}</td>
                                        <td class="px-5 py-3 text-sm font-medium" style="color: #e8e4dc;">₱{{ number_format($txn->total_amount, 2) }}</td>
                                        <td class="px-5 py-3">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-medium"
                                                @if ($txn->status === 'completed')
                                                    style="background: rgba(45,133,112,0.12); color: #5cbfaa; border: 1px solid rgba(45,133,112,0.12);"
                                                @elseif ($txn->status === 'voided')
                                                    style="background: rgba(180,50,50,0.12); color: #f0a0a0; border: 1px solid rgba(180,50,50,0.12);"
                                                @else
                                                    style="background: rgba(200,155,60,0.1); color: #d4b060; border: 1px solid rgba(200,155,60,0.1);"
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
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('cashier.transactions') }}" wire:navigate class="stat-card rounded-2xl p-5 block transition-all duration-300"
                   style="background: rgba(255,255,255,0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.06);">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium" style="color: #6a6560;">Today's Sales</p>
                            <p class="text-2xl font-bold mt-1" style="color: #e8e4dc;">₱{{ number_format($todaySales, 2) }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(45,133,112,0.12); border: 1px solid rgba(45,133,112,0.1);">
                            <svg class="w-5 h-5" style="color: #5cbfaa;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </a>

                <a href="{{ route('cashier.transactions') }}" wire:navigate class="stat-card rounded-2xl p-5 block transition-all duration-300"
                   style="background: rgba(255,255,255,0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.06);">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium" style="color: #6a6560;">Transactions</p>
                            <p class="text-2xl font-bold mt-1" style="color: #e8e4dc;">{{ $todayTransactions }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(200,155,60,0.1); border: 1px solid rgba(200,155,60,0.1);">
                            <svg class="w-5 h-5" style="color: #d4b060;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                            </svg>
                        </div>
                    </div>
                </a>

                <a href="{{ route('cashier.transactions') }}" wire:navigate class="stat-card rounded-2xl p-5 block transition-all duration-300"
                   style="background: rgba(255,255,255,0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.06);">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium" style="color: #6a6560;">Items Sold</p>
                            <p class="text-2xl font-bold mt-1" style="color: #e8e4dc;">{{ $todayItems }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(61,139,139,0.1); border: 1px solid rgba(61,139,139,0.1);">
                            <svg class="w-5 h-5" style="color: #5aa8a8;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                        </div>
                    </div>
                </a>

                <a href="{{ route('cashier.transactions') }}" wire:navigate class="stat-card rounded-2xl p-5 block transition-all duration-300"
                   style="background: rgba(255,255,255,0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.06);">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium" style="color: #6a6560;">My Commission</p>
                            <p class="text-2xl font-bold mt-1" style="color: #e8e4dc;">₱{{ number_format($todayCommission, 2) }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(200,155,60,0.1); border: 1px solid rgba(200,155,60,0.08);">
                            <svg class="w-5 h-5" style="color: #d4b060;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                            </svg>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Cashier Quick Actions --}}
            <div>
                <h2 class="text-xs font-semibold uppercase tracking-widest mb-3" style="color: #6a6560;">Quick Actions</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <a href="{{ route('cashier.checkout') }}" wire:navigate class="group flex items-center gap-4 rounded-2xl p-4 transition-all duration-300"
                       style="background: rgba(255,255,255,0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.06);"
                       onmouseover="this.style.borderColor='rgba(45,133,112,0.2)'; this.style.boxShadow='0 4px 24px rgba(45,133,112,0.06)';"
                       onmouseout="this.style.borderColor='rgba(255,255,255,0.06)'; this.style.boxShadow='none';">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(45,133,112,0.1); border: 1px solid rgba(45,133,112,0.08);">
                            <svg class="w-5 h-5" style="color: #5cbfaa;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121 0 2.09-.773 2.34-1.872l1.836-8.046A1.125 1.125 0 0018.054 3H5.106m2.394 11.25l-1.5-6h13.5" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold" style="color: #e8e4dc;">Checkout</p>
                            <p class="text-xs" style="color: #6a6560;">Process a sale</p>
                        </div>
                    </a>

                    <a href="{{ route('cashier.transactions') }}" wire:navigate class="group flex items-center gap-4 rounded-2xl p-4 transition-all duration-300"
                       style="background: rgba(255,255,255,0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.06);">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(200,155,60,0.1); border: 1px solid rgba(200,155,60,0.08);">
                            <svg class="w-5 h-5" style="color: #d4b060;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold" style="color: #e8e4dc;">Transactions</p>
                            <p class="text-xs" style="color: #6a6560;">View today's sales</p>
                        </div>
                    </a>

                    <a href="{{ route('cashier.inbox') }}" wire:navigate class="group flex items-center gap-4 rounded-2xl p-4 transition-all duration-300"
                       style="background: rgba(255,255,255,0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.06);">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center relative" style="background: rgba(200,155,60,0.08); border: 1px solid rgba(200,155,60,0.08);">
                            <svg class="w-5 h-5" style="color: #d4b060;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                            @if (auth()->user()->unreadMessagesCount() > 0)
                                <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full flex items-center justify-center text-[9px] font-bold"
                                      style="background: #c89b3c; color: #0d0f10;">
                                    {{ auth()->user()->unreadMessagesCount() }}
                                </span>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-semibold" style="color: #e8e4dc;">Messages</p>
                            <p class="text-xs" style="color: #6a6560;">Contact admin</p>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Recent Transactions --}}
            @if ($recentTransactions->count() > 0)
                <div class="rounded-2xl overflow-hidden"
                     style="background: rgba(255,255,255,0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.06);">
                    <div class="px-5 py-4 flex items-center justify-between" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                        <h2 class="text-sm font-semibold" style="color: #e8e4dc;">Recent Transactions</h2>
                        <a href="{{ route('cashier.transactions') }}" wire:navigate class="text-xs font-medium transition-all duration-200" style="color: #5cbfaa;" onmouseover="this.style.color='#7ed4bf';" onmouseout="this.style.color='#5cbfaa';">View all &rarr;</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: #6a6560;">Time</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: #6a6560;">Items</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: #6a6560;">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentTransactions as $txn)
                                    <tr class="transition-all duration-200" style="border-bottom: 1px solid rgba(255,255,255,0.03);"
                                        onmouseover="this.style.background='rgba(255,255,255,0.02)';"
                                        onmouseout="this.style.background='';">
                                        <td class="px-5 py-3 text-sm" style="color: #6a6560;">{{ $txn->created_at->format('g:i A') }}</td>
                                        <td class="px-5 py-3 text-sm" style="color: #8a8580;">{{ $txn->lineItems->sum('quantity') }}</td>
                                        <td class="px-5 py-3 text-sm font-medium" style="color: #e8e4dc;">₱{{ number_format($txn->total_amount, 2) }}</td>
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
