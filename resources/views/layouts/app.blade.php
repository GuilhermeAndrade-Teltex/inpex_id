<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="url-base" content="{{ env("APP_URL") }}">

    <title>{{ config('app.name', 'Teltex') }}</title>

    <link rel="icon" href="{{ asset('images/logos/inpexid.svg') }}" type="image/x-icon">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/css/theme.css', 'resources/js/app.js', 'resources/js/theme.js', 'resources/js/custom.js'])

</head>

<body class="font-sans antialiased">
    <section class="body">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <!-- Page Heading -->
            @include('components.header')

            <!-- Page Content -->
            <div class="inner-wrapper">
                @isset($allowedMenus)
                    @include('components.left-sidebar', ['allowedMenus' => $allowedMenus])
                @else
                    @include('components.left-sidebar')
                @endisset
                @yield('main')
                @include('components.footer')
            </div>
            @include('components.right-sidebar')
        </div>
    </section>
</body>

</html>