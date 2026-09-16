<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    {{-- Desktop Sidebar --}}
    <aside
        class="hidden lg:flex flex-col w-64 text-white overflow-y-auto shrink-0 h-screen fixed inset-y-0 left-0 z-30"
        style="background: rgba(12, 14, 17, 0.85); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px); border-right: 1px solid rgba(255,255,255,0.06);"
    >
        <div class="flex items-center gap-3 h-16 px-5 shrink-0" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
            <div class="flex items-center justify-center w-9 h-9 rounded-xl" style="background: rgba(45,133,112,0.15); border: 1px solid rgba(45,133,112,0.2);">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-6 w-6 object-contain" />
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-bold tracking-wide leading-tight" style="color: #e8e4dc;">TRIBU PAKARAS</span>
                <span class="text-[10px] leading-tight" style="color: #5cbfaa;">POS System</span>
            </div>
        </div>

        <nav class="flex-1 px-3 py-4 space-y-1">
            <a href="{{ route('dashboard') }}" wire:navigate
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('dashboard') ? '' : '' }}"
               @if (request()->routeIs('dashboard'))
                   style="background: rgba(45,133,112,0.12); border: 1px solid rgba(45,133,112,0.18); color: #e8e4dc; box-shadow: 0 0 20px rgba(45,133,112,0.08);"
               @else
                   style="color: #8a8580; border: 1px solid transparent;"
                   onmouseover="this.style.background='rgba(255,255,255,0.04)'; this.style.color='#e8e4dc'; this.style.borderColor='rgba(255,255,255,0.06)';"
                   onmouseout="this.style.background=''; this.style.color='#8a8580'; this.style.borderColor='transparent';"
               @endif
            >
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                </svg>
                Dashboard
            </a>

            @if (auth()->user()->isAdmin())
                <a href="{{ route('admin.products') }}" wire:navigate
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200"
                   @if (request()->routeIs('admin.products'))
                       style="background: rgba(45,133,112,0.12); border: 1px solid rgba(45,133,112,0.18); color: #e8e4dc; box-shadow: 0 0 20px rgba(45,133,112,0.08);"
                   @else
                       style="color: #8a8580; border: 1px solid transparent;"
                       onmouseover="this.style.background='rgba(255,255,255,0.04)'; this.style.color='#e8e4dc'; this.style.borderColor='rgba(255,255,255,0.06)';"
                       onmouseout="this.style.background=''; this.style.color='#8a8580'; this.style.borderColor='transparent';"
                   @endif
                >
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                    Products
                </a>

                <a href="{{ route('admin.discounts') }}" wire:navigate
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200"
                   @if (request()->routeIs('admin.discounts'))
                       style="background: rgba(200,155,60,0.1); border: 1px solid rgba(200,155,60,0.15); color: #e8e4dc; box-shadow: 0 0 20px rgba(200,155,60,0.06);"
                   @else
                       style="color: #8a8580; border: 1px solid transparent;"
                       onmouseover="this.style.background='rgba(255,255,255,0.04)'; this.style.color='#e8e4dc'; this.style.borderColor='rgba(255,255,255,0.06)';"
                       onmouseout="this.style.background=''; this.style.color='#8a8580'; this.style.borderColor='transparent';"
                   @endif
                >
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                    </svg>
                    Discounts
                </a>

                <a href="{{ route('admin.reports') }}" wire:navigate
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200"
                   @if (request()->routeIs('admin.reports'))
                       style="background: rgba(61,139,139,0.1); border: 1px solid rgba(61,139,139,0.15); color: #e8e4dc; box-shadow: 0 0 20px rgba(61,139,139,0.06);"
                   @else
                       style="color: #8a8580; border: 1px solid transparent;"
                       onmouseover="this.style.background='rgba(255,255,255,0.04)'; this.style.color='#e8e4dc'; this.style.borderColor='rgba(255,255,255,0.06)';"
                       onmouseout="this.style.background=''; this.style.color='#8a8580'; this.style.borderColor='transparent';"
                   @endif
                >
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                    Reports
                </a>

                <a href="{{ route('admin.users') }}" wire:navigate
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200"
                   @if (request()->routeIs('admin.users'))
                       style="background: rgba(45,133,112,0.12); border: 1px solid rgba(45,133,112,0.18); color: #e8e4dc; box-shadow: 0 0 20px rgba(45,133,112,0.08);"
                   @else
                       style="color: #8a8580; border: 1px solid transparent;"
                       onmouseover="this.style.background='rgba(255,255,255,0.04)'; this.style.color='#e8e4dc'; this.style.borderColor='rgba(255,255,255,0.06)';"
                       onmouseout="this.style.background=''; this.style.color='#8a8580'; this.style.borderColor='transparent';"
                   @endif
                >
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                    Users
                </a>

                <a href="{{ route('admin.transactions') }}" wire:navigate
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200"
                   @if (request()->routeIs('admin.transactions'))
                       style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08); color: #e8e4dc;"
                   @else
                       style="color: #8a8580; border: 1px solid transparent;"
                       onmouseover="this.style.background='rgba(255,255,255,0.04)'; this.style.color='#e8e4dc'; this.style.borderColor='rgba(255,255,255,0.06)';"
                       onmouseout="this.style.background=''; this.style.color='#8a8580'; this.style.borderColor='transparent';"
                   @endif
                >
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                    </svg>
                    Transactions
                </a>
            @endif

            @if (auth()->user()->isCashier())
                <a href="{{ route('cashier.checkout') }}" wire:navigate
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200"
                   @if (request()->routeIs('cashier.checkout'))
                       style="background: rgba(45,133,112,0.12); border: 1px solid rgba(45,133,112,0.18); color: #e8e4dc; box-shadow: 0 0 20px rgba(45,133,112,0.08);"
                   @else
                       style="color: #8a8580; border: 1px solid transparent;"
                       onmouseover="this.style.background='rgba(255,255,255,0.04)'; this.style.color='#e8e4dc'; this.style.borderColor='rgba(255,255,255,0.06)';"
                       onmouseout="this.style.background=''; this.style.color='#8a8580'; this.style.borderColor='transparent';"
                   @endif
                >
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                    Checkout
                </a>

                <a href="{{ route('cashier.transactions') }}" wire:navigate
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200"
                   @if (request()->routeIs('cashier.transactions'))
                       style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08); color: #e8e4dc;"
                   @else
                       style="color: #8a8580; border: 1px solid transparent;"
                       onmouseover="this.style.background='rgba(255,255,255,0.04)'; this.style.color='#e8e4dc'; this.style.borderColor='rgba(255,255,255,0.06)';"
                       onmouseout="this.style.background=''; this.style.color='#8a8580'; this.style.borderColor='transparent';"
                   @endif
                >
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                    </svg>
                    My Transactions
                </a>
            @endif
        </nav>

        <div class="px-4 py-3 shrink-0" style="border-top: 1px solid rgba(255,255,255,0.06);">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center font-semibold text-sm shrink-0" style="background: rgba(45,133,112,0.2); color: #5cbfaa; border: 1px solid rgba(45,133,112,0.15);">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium truncate" style="color: #e8e4dc;">{{ auth()->user()->name }}</div>
                    <div class="text-xs truncate" style="color: #5cbfaa;">{{ ucfirst(auth()->user()->role) }}</div>
                </div>
                <button wire:click="logout" title="Log Out" class="p-1.5 rounded-lg transition-all duration-200" style="color: #6a6560;" onmouseover="this.style.background='rgba(255,255,255,0.06)'; this.style.color='#e8e4dc';" onmouseout="this.style.background=''; this.style.color='#6a6560';">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                    </svg>
                </button>
            </div>
        </div>
    </aside>

    {{-- Mobile Overlay --}}
    <div x-show="mobileOpen" x-transition:enter="transition-opacity ease-linear duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @click="mobileOpen = false"
         class="fixed inset-0 z-40 lg:hidden" style="background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);" style="display: none;">
    </div>

    {{-- Mobile Sidebar --}}
    <aside
        x-show="mobileOpen"
        x-transition:enter="transition ease-in-out duration-200"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in-out duration-200"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed inset-y-0 left-0 z-50 w-64 text-white overflow-y-auto lg:hidden"
        style="background: rgba(12, 14, 17, 0.92); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px); border-right: 1px solid rgba(255,255,255,0.06); display: none;"
    >
        <div class="flex items-center justify-between h-16 px-5 shrink-0" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-9 h-9 rounded-xl" style="background: rgba(45,133,112,0.15); border: 1px solid rgba(45,133,112,0.2);">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-6 w-6 object-contain" />
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-bold tracking-wide leading-tight" style="color: #e8e4dc;">TRIBU PAKARAS</span>
                    <span class="text-[10px] leading-tight" style="color: #5cbfaa;">POS System</span>
                </div>
            </div>
            <button @click="mobileOpen = false" class="p-1 rounded-lg transition-all duration-200" style="color: #6a6560;" onmouseover="this.style.color='#e8e4dc';" onmouseout="this.style.color='#6a6560';">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <nav class="px-3 py-4 space-y-1">
            <a href="{{ route('dashboard') }}" wire:navigate @click="mobileOpen = false"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200"
               @if (request()->routeIs('dashboard'))
                   style="background: rgba(45,133,112,0.12); border: 1px solid rgba(45,133,112,0.18); color: #e8e4dc;"
               @else
                   style="color: #8a8580; border: 1px solid transparent;"
               @endif
            >
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                </svg>
                Dashboard
            </a>

            @if (auth()->user()->isAdmin())
                <a href="{{ route('admin.products') }}" wire:navigate @click="mobileOpen = false"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200"
                   @if (request()->routeIs('admin.products'))
                       style="background: rgba(45,133,112,0.12); border: 1px solid rgba(45,133,112,0.18); color: #e8e4dc;"
                   @else
                       style="color: #8a8580; border: 1px solid transparent;"
                   @endif
                >
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                    Products
                </a>
                <a href="{{ route('admin.discounts') }}" wire:navigate @click="mobileOpen = false"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200"
                   @if (request()->routeIs('admin.discounts'))
                       style="background: rgba(200,155,60,0.1); border: 1px solid rgba(200,155,60,0.15); color: #e8e4dc;"
                   @else
                       style="color: #8a8580; border: 1px solid transparent;"
                   @endif
                >
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                    </svg>
                    Discounts
                </a>
                <a href="{{ route('admin.reports') }}" wire:navigate @click="mobileOpen = false"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200"
                   @if (request()->routeIs('admin.reports'))
                       style="background: rgba(61,139,139,0.1); border: 1px solid rgba(61,139,139,0.15); color: #e8e4dc;"
                   @else
                       style="color: #8a8580; border: 1px solid transparent;"
                   @endif
                >
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                    Reports
                </a>
                <a href="{{ route('admin.users') }}" wire:navigate @click="mobileOpen = false"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200"
                   @if (request()->routeIs('admin.users'))
                       style="background: rgba(45,133,112,0.12); border: 1px solid rgba(45,133,112,0.18); color: #e8e4dc;"
                   @else
                       style="color: #8a8580; border: 1px solid transparent;"
                   @endif
                >
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                    Users
                </a>
                <a href="{{ route('admin.transactions') }}" wire:navigate @click="mobileOpen = false"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200"
                   @if (request()->routeIs('admin.transactions'))
                       style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08); color: #e8e4dc;"
                   @else
                       style="color: #8a8580; border: 1px solid transparent;"
                   @endif
                >
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                    </svg>
                    Transactions
                </a>
            @endif

            @if (auth()->user()->isCashier())
                <a href="{{ route('cashier.checkout') }}" wire:navigate @click="mobileOpen = false"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200"
                   @if (request()->routeIs('cashier.checkout'))
                       style="background: rgba(45,133,112,0.12); border: 1px solid rgba(45,133,112,0.18); color: #e8e4dc;"
                   @else
                       style="color: #8a8580; border: 1px solid transparent;"
                   @endif
                >
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                    Checkout
                </a>
                <a href="{{ route('cashier.transactions') }}" wire:navigate @click="mobileOpen = false"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200"
                   @if (request()->routeIs('cashier.transactions'))
                       style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08); color: #e8e4dc;"
                   @else
                       style="color: #8a8580; border: 1px solid transparent;"
                   @endif
                >
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                    </svg>
                    My Transactions
                </a>
            @endif
        </nav>

        <div class="px-4 py-3 shrink-0" style="border-top: 1px solid rgba(255,255,255,0.06);">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center font-semibold text-sm shrink-0" style="background: rgba(45,133,112,0.2); color: #5cbfaa; border: 1px solid rgba(45,133,112,0.15);">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium truncate" style="color: #e8e4dc;">{{ auth()->user()->name }}</div>
                    <div class="text-xs truncate" style="color: #5cbfaa;">{{ ucfirst(auth()->user()->role) }}</div>
                </div>
                <button wire:click="logout" title="Log Out" class="p-1.5 rounded-lg transition-all duration-200" style="color: #6a6560;">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                    </svg>
                </button>
            </div>
        </div>
    </aside>
</div>
