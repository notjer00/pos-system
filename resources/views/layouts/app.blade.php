<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => ($appearance ?? 'system') == 'dark']) data-appearance="{{ in_array($appearance ?? 'system', ['light', 'dark', 'system'], true) ? $appearance : 'system' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Elevation POS') }}</title>

        <x-theme-init />

        <link rel="icon" href="/images/ELEVATION.jpg.jpg?v=2" type="image/jpeg">
        <link rel="apple-touch-icon" href="/images/ELEVATION.jpg.jpg" type="image/jpeg">

        <!-- Fonts -->

        <!-- Styles -->
        @vite('resources/css/app.css')

        <!-- Livewire Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-background" x-data="{ mobileOpen: false }">

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

                    <!-- Theme selector -->
                    <div class="theme-selector" role="group" aria-label="Appearance mode">
                        <button type="button" data-theme-mode="light"
                                onclick="window.ElevationTheme && window.ElevationTheme.set('light')"
                                title="Light mode" aria-label="Light mode">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="4"></circle>
                                <path d="M12 2v2"></path>
                                <path d="M12 20v2"></path>
                                <path d="m4.93 4.93 1.41 1.41"></path>
                                <path d="m17.66 17.66 1.41 1.41"></path>
                                <path d="M2 12h2"></path>
                                <path d="M20 12h2"></path>
                                <path d="m6.34 17.66-1.41 1.41"></path>
                                <path d="m19.07 4.93-1.41 1.41"></path>
                            </svg>
                        </button>
                        <button type="button" data-theme-mode="dark"
                                onclick="window.ElevationTheme && window.ElevationTheme.set('dark')"
                                title="Dark mode" aria-label="Dark mode">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path>
                            </svg>
                        </button>
                        <button type="button" data-theme-mode="system"
                                onclick="window.ElevationTheme && window.ElevationTheme.set('system')"
                                title="System mode" aria-label="System mode">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect width="20" height="14" x="2" y="3" rx="2"></rect>
                                <path d="M8 21h8"></path>
                                <path d="M12 17v4"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- User dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="btn-ghost gap-2 px-2 py-1.5 rounded-lg">
                            <div class="w-7 h-7 rounded-md flex items-center justify-center font-semibold text-[11px] shrink-0" style="background: rgba(200,155,60,0.15); color: hsl(var(--gold-text));">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="hidden sm:inline font-medium text-[13px]" style="color: hsl(var(--muted-foreground));">{{ auth()->user()->name }}</span>
                            <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 20 20" style="color: hsl(var(--muted-foreground));">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition
                             class="absolute right-0 mt-1.5 w-48 rounded-xl shadow-xl z-50 overflow-hidden"
                             style="background: hsl(var(--popover)); border: 1px solid hsl(var(--border)); display: none;">
                            <div class="px-3 py-2 text-[11px] font-semibold uppercase tracking-wider" style="color: hsl(var(--muted-foreground)); border-bottom: 1px solid hsl(var(--border));">
                                {{ ucfirst(auth()->user()->role) }}
                            </div>
                            <a href="{{ route('profile') }}" wire:navigate
                               class="um-dropdown-item">
                                Profile
                            </a>
                            <a href="{{ route('profile.edit') }}"
                               class="um-dropdown-item">
                                Settings
                            </a>
                            <a href="{{ route('appearance.edit') }}"
                               class="um-dropdown-item">
                                Appearance
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
