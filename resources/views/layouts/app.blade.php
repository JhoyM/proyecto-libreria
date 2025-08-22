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
        <script>
            (function () {
                try {
                    const saved = localStorage.getItem('theme') || 'corporate';
                    document.documentElement.setAttribute('data-theme', saved);
                    window.setTheme = function(theme){
                        localStorage.setItem('theme', theme);
                        document.documentElement.setAttribute('data-theme', theme);
                    };
                } catch (e) {
                    // fallback
                    document.documentElement.setAttribute('data-theme', 'corporate');
                }
            })();
        </script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body class="font-sans antialiased bg-base-200 text-base-content">
        <div class="min-h-screen bg-base-200">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (View::hasSection('header'))
                <header class="bg-base-100 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        @yield('header')
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
        </div>
        @stack('scripts')
    </body>
</html>
