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
            'resources/css/app.css',
            'resources/js/app.js'
        ])

        <!-- 💡 Correcciones de contraste, dashboard y menú -->
        <style>
            body {
                background-color: #f8f9fa; /* Fondo claro */
                color: #212529;            /* Texto oscuro */
            }

            header, nav, footer {
                background-color: #ffffff;
                color: #212529;
            }

            a, .text-primary {
                color: #0d6efd;
            }

            h1, h2, h3, h4, h5 {
                color: #1a1a1a;
            }

            .card {
                background-color: #fff;
                color: #212529;
                border: 1px solid #ddd;
                border-radius: 8px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            }

            table {
                color: #212529;
            }

            /* 🔹 Corrige texto demasiado claro del dashboard */
            .text-gray-400, .text-gray-500, .text-gray-600, .text-gray-700 {
                color: #212529 !important;
            }

            /* 🔹 Hace visibles los íconos del header (logo de Laravel, etc.) */
            header svg, header span, header a,
            nav svg, nav span, nav a, nav div, nav li {
                color: #212529 !important;
                fill: #212529 !important;
                stroke: #212529 !important;
            }

            /* 🔹 Corrige fondo gris claro del dashboard */
            .bg-gray-100, .bg-gray-50 {
                background-color: #ffffff !important;
            }

            /* 🔹 Mejora visual de las tarjetas */
            .shadow-sm, .shadow, .shadow-lg {
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12) !important;
                border-color: #ccc !important;
            }

            /* 🔹 Corrige el menú desplegable del usuario */
            [role="menu"] {
                background-color: #ffffff !important;
                color: #212529 !important;
                border: 1px solid #ccc !important;
                box-shadow: 0 2px 10px rgba(0,0,0,0.15) !important;
            }

            [role="menu"] a,
            [role="menu"] button {
                color: #212529 !important;
                font-weight: 500;
            }

            [role="menu"] a:hover,
            [role="menu"] button:hover {
                background-color: #f2f2f2 !important;
                color: #000 !important;
            }

            /* 🔹 Corrige el color del nombre de usuario en el navbar */
            .font-medium.text-gray-500 {
                color: #212529 !important;
            }

            /* 🔹 Corrige el texto del navbar oscuro */
            nav.navbar, 
            nav.navbar a.navbar-brand, 
            nav.navbar .nav-link,
            nav.navbar .navbar-toggler-icon,
            nav.navbar span,
            nav.navbar div {
                color: #f8f9fa !important;   /* Texto claro */
                fill: #f8f9fa !important;
                stroke: #f8f9fa !important;
            }

            /* 🔹 Color de hover del texto del menú */
            nav.navbar a.nav-link:hover {
                color: #ffffff !important;
            }
        </style>
    </head>

    <body class="font-sans antialiased bg-light text-dark">
        <div class="min-h-screen bg-light text-dark">
            @include('layouts.navigation')

            <!-- Encabezado -->
            @if (isset($header) || View::hasSection('header'))
                <header class="bg-white shadow-sm border-bottom">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        @isset($header)
                            {{ $header }}
                        @else
                            @yield('header')
                        @endisset
                    </div>
                </header>
            @endif

            <!-- Contenido principal -->
            <main>
                @if (isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </main>
        </div>
    </body>
</html>

