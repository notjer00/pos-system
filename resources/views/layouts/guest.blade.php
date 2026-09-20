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

        <!-- Livewire Scripts -->
        @livewireScripts
    </head>
    <body class="font-sans antialiased" style="background: #0d0f10; color: #e0dcd4;">
        {{ $slot }}
    </body>
</html>
