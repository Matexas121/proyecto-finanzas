<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite([
        'resources/css/app.css', // Si importas Bootstrap vía SCSS
        'resources/js/app.js'    // Si importas Bootstrap vía JS
    ])
    </head>
    <body class="font-sans antialiased">
        <div class="bg-light">
            @include('layouts.navigation')

            <!-- Page Heading -->
            {{-- VERIFICAMOS SI $header EXISTE (USO COMO COMPONENTE) O BUSCAMOS @yield('header') (USO CON @extends) --}}
            @if (isset($header) || View::hasSection('header'))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        @isset($header)
                            {{ $header }}
                        @else
                            @yield('header') {{-- Si no está definido el slot, busca el yield --}}
                        @endisset
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{-- VERIFICAMOS SI $slot EXISTE (USO COMO COMPONENTE) O BUSCAMOS @yield('content') (USO CON @extends) --}}
                @if (isset($slot))
                    {{ $slot }}
                @else
                    @yield('content') {{-- Si no está definido el slot, busca el yield --}}
                @endif
            </main>
        </div>
    </body>
</html>
