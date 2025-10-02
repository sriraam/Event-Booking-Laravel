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

        <!--Custom Css script -->
        <link rel = "stylesheet" href="{{ asset('css/eventBooking.css')}}">
        <link rel = "stylesheet" href="{{ asset('css/messages.css')}}">
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @include('layouts.flash') <!--Displays flash alert -->
                {{ $slot }}

            <!--js choices script -->
            <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
            <!--js wrapper script for categories-->
            <script src="{{ asset('js/CategoriesChoice.js') }}"></script>    
        </main>
        </div>
        <script>
            setTimeout(() => {
             document.querySelectorAll('.alert').forEach(el => el.remove());
                }, 3000);
        </script>
    </body>
</html>
