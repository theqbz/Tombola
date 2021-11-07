<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="overflow-x:hidden">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('meta')
    <title>{{ config('app.name', 'Ticketto') }}</title>
    <script src="{{ asset('js/app.js') }}"></script>

<!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-dark">
@include('layouts.header')

<div id="app" class="pt-4 pb-4" style="min-height: 75vh">
    <main >
        @yield('content')
    </main>
</div>
@include('layouts.footer')
<!-- Scripts -->
@stack('scripts')

</body>
</html>
