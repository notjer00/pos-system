<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => ($appearance ?? 'system') == 'dark']) data-appearance="{{ in_array($appearance ?? 'system', ['light', 'dark', 'system'], true) ? $appearance : 'system' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- Apply stored appearance before hydration to avoid a flash of the wrong theme --}}
        <x-theme-init />

        <link rel="icon" href="/images/ELEVATION.jpg.jpg?v=2" type="image/jpeg">
        <link rel="apple-touch-icon" href="/images/ELEVATION.jpg.jpg" type="image/jpeg">

        @fonts

        @viteReactRefresh
        @vite(['resources/css/app.css', 'resources/js/app.tsx', "resources/js/pages/{$page['component']}.tsx"])
        <x-inertia::head>
            <title>{{ config('app.name', 'Laravel') }}</title>
        </x-inertia::head>
    </head>
    <body class="font-sans antialiased">
        <x-inertia::app />
    </body>
</html>
