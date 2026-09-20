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
       
        <!-- Styles -->
        @vite('resources/css/app.css')

        <!-- Livewire Scripts -->
         @livewireStyles
        @livewireScripts
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen" x-data="{ mobileOpen: false }"
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
