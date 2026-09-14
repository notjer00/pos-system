<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Tribu Pakaras POS') }}</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.png" type="image/png">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite('resources/css/app.css')

        <!-- Livewire Scripts -->
        @livewireScripts
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen" x-data="{ mobileOpen: false, notifOpen: false }"
             style="background: #0d0f10;">
            {{-- Ambient background lighting --}}
            <div class="fixed inset-0 pointer-events-none z-0" aria-hidden="true">
                <div class="absolute top-[-20%] left-[-10%] w-[500px] h-[500px] rounded-full" style="background: radial-gradient(circle, rgba(45,133,112,0.06) 0%, transparent 70%); filter: blur(80px);"></div>
                <div class="absolute bottom-[-10%] right-[-5%] w-[400px] h-[400px] rounded-full" style="background: radial-gradient(circle, rgba(200,155,60,0.04) 0%, transparent 70%); filter: blur(80px);"></div>
            </div>

            <!-- Sidebar -->
            <livewire:layout.navigation />

            <!-- Main Content (offset by sidebar width on desktop) -->
            <div class="lg:ml-64 flex flex-col min-h-screen relative z-10">
                <!-- Top Bar -->
                <header class="flex items-center h-16 px-4 sm:px-6 shrink-0 sticky top-0 z-20"
                        style="background: rgba(12, 14, 17, 0.75); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border-bottom: 1px solid rgba(255,255,255,0.06);">
                    <!-- Mobile hamburger -->
                    <button @click="mobileOpen = !mobileOpen" class="lg:hidden p-2 -ml-2 rounded-xl transition-all duration-200" style="color: #8a8580;" onmouseover="this.style.color='#e8e4dc';" onmouseout="this.style.color='#8a8580';">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div class="flex-1"></div>

                    <!-- Notification Bell -->
                    <div class="relative mr-3">
                        <button @click="notifOpen = !notifOpen" class="relative p-2 rounded-xl transition-all duration-200"
                                style="color: #c0bbb0;" onmouseover="this.style.background='rgba(255,255,255,0.06)'; this.style.color='#e8e4dc';" onmouseout="this.style.background=''; this.style.color='#c0bbb0';">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                            </svg>
                            @if (auth()->user()->unreadMessagesCount() > 0)
                                <span class="absolute -top-0.5 -right-0.5 w-4 h-4 rounded-full flex items-center justify-center text-[9px] font-bold"
                                      style="background: #c89b3c; color: #0d0f10;">
                                    {{ auth()->user()->unreadMessagesCount() > 9 ? '9+' : auth()->user()->unreadMessagesCount() }}
                                </span>
                            @endif
                        </button>

                        <!-- Notification Dropdown -->
                        <div x-show="notifOpen"
                             @click.away="notifOpen = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                             class="absolute right-0 mt-2 w-80 rounded-2xl shadow-2xl z-50 overflow-hidden"
                             style="background: rgba(18, 20, 24, 0.92); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px); border: 1px solid rgba(255,255,255,0.08); display: none;"
                             x-cloak>
                            <div class="px-4 py-3 flex items-center justify-between" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                                <h3 class="text-sm font-semibold" style="color: #e8e4dc;">Notifications</h3>
                                @if (auth()->user()->unreadMessagesCount() > 0)
                                    <span class="text-xs px-2 py-0.5 rounded-full font-medium" style="background: rgba(200,155,60,0.15); color: #d4b060; border: 1px solid rgba(200,155,60,0.15);">
                                        {{ auth()->user()->unreadMessagesCount() }} unread
                                    </span>
                                @endif
                            </div>

                            <div class="max-h-64 overflow-y-auto">
                                @if (auth()->user()->unreadMessagesCount() > 0)
                                    <a href="{{ route(auth()->user()->isAdmin() ? 'admin.inbox' : 'cashier.inbox') }}" wire:navigate
                                       class="flex items-start gap-3 px-4 py-3 transition-all duration-200"
                                       style="border-bottom: 1px solid rgba(255,255,255,0.04);"
                                       onmouseover="this.style.background='rgba(255,255,255,0.04)';"
                                       onmouseout="this.style.background='';">
                                        <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mt-0.5" style="background: rgba(45,133,112,0.15); border: 1px solid rgba(45,133,112,0.15);">
                                            <svg class="w-4 h-4" style="color: #5cbfaa;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium" style="color: #e8e4dc;">You have {{ auth()->user()->unreadMessagesCount() }} unread message{{ auth()->user()->unreadMessagesCount() > 1 ? 's' : '' }}</p>
                                            <p class="text-xs mt-0.5" style="color: #6a6560;">Click to view your inbox</p>
                                        </div>
                                    </a>
                                @else
                                    <div class="flex flex-col items-center justify-center py-10 px-4">
                                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-3" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06);">
                                            <svg class="w-6 h-6" style="color: #4a4540;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium" style="color: #8a8580;">No new notifications</p>
                                        <p class="text-xs mt-1" style="color: #4a4540;">You're all caught up</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- User dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-2 text-sm focus:outline-none rounded-xl px-2 py-1.5 transition-all duration-200"
                                style="color: #c0bbb0;" onmouseover="this.style.background='rgba(255,255,255,0.04)';" onmouseout="this.style.background='';">
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center font-semibold text-xs" style="background: rgba(45,133,112,0.2); color: #5cbfaa; border: 1px solid rgba(45,133,112,0.15);">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="hidden sm:inline font-medium" style="color: #e8e4dc;">{{ auth()->user()->name }}</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 20 20" style="color: #6a6560;">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition
                             class="absolute right-0 mt-2 w-52 rounded-2xl shadow-2xl z-50 overflow-hidden"
                             style="background: rgba(18, 20, 24, 0.92); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px); border: 1px solid rgba(255,255,255,0.08);">
                            <div class="px-4 py-2.5 text-xs font-medium" style="color: #5cbfaa; border-bottom: 1px solid rgba(255,255,255,0.06);">
                                {{ ucfirst(auth()->user()->role) }}
                            </div>
                            <a href="{{ route('profile') }}" wire:navigate class="block px-4 py-2.5 text-sm transition-all duration-200"
                               style="color: #c0bbb0; border-bottom: 1px solid rgba(255,255,255,0.04);"
                               onmouseover="this.style.background='rgba(255,255,255,0.04)'; this.style.color='#e8e4dc';"
                               onmouseout="this.style.background=''; this.style.color='#c0bbb0';">
                                Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm transition-all duration-200"
                                        style="color: #c0bbb0;"
                                        onmouseover="this.style.background='rgba(255,255,255,0.04)'; this.style.color='#e8e4dc';"
                                        onmouseout="this.style.background=''; this.style.color='#c0bbb0';">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 p-4 sm:p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
