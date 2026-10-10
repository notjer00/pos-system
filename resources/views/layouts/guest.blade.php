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
        {{ $slot }}

        <!-- Livewire Scripts (must be before closing body tag) -->
        @livewireScripts
    </body>
</html>
