<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Elevation POS') }}</title>

        <link rel="icon" href="/images/ELEVATION.jpg.jpg?v=2" type="image/jpeg">
        <link rel="apple-touch-icon" href="/images/ELEVATION.jpg.jpg" type="image/jpeg">

        <!-- Fonts -->
       
        <!-- Styles -->
        @vite('resources/css/app.css')

        <!-- Livewire Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen" x-data="{ mobileOpen: false }"
             style="background: #0d0f10;">

            <!-- Sidebar -->
            <livewire:layout.navigation />

            <!-- Main Content (offset by sidebar width on desktop) -->
            <div class="lg:ml-60 flex flex-col min-h-screen relative z-10">
                <!-- Top Bar -->
                <header class="app-header">
                    <!-- Mobile hamburger -->
                    <button @click="mobileOpen = !mobileOpen" class="lg:hidden btn-icon -ml-1">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div class="flex-1"></div>

                    <!-- User dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="btn-ghost gap-2 px-2 py-1.5 rounded-lg">
                            <div class="w-7 h-7 rounded-md flex items-center justify-center font-semibold text-[11px] shrink-0" style="background: rgba(200,155,60,0.15); color: #d4a850;">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="hidden sm:inline font-medium text-[13px]" style="color: #a09890;">{{ auth()->user()->name }}</span>
                            <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 20 20" style="color: #706860;">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition
                             class="absolute right-0 mt-1.5 w-48 rounded-xl shadow-xl z-50 overflow-hidden"
                             style="background: #14161a; border: 1px solid rgba(255,255,255,0.08); display: none;">
                            <div class="px-3 py-2 text-[11px] font-semibold uppercase tracking-wider" style="color: #706860; border-bottom: 1px solid rgba(255,255,255,0.06);">
                                {{ ucfirst(auth()->user()->role) }}
                            </div>
                            <a href="{{ route('profile') }}" wire:navigate
                               class="um-dropdown-item">
                                Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left um-dropdown-item">
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

        <!-- Livewire Scripts (must be before closing body tag) -->
        @livewireScripts
    </body>
</html>
