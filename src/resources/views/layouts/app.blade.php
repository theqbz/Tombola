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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="../css/ticketto.css"
</head>
<body class="d-flex flex-column h-100">
@include('layouts.header')

<!--<div id="app" class="pt-4 pb-4" style="min-height: 75vh">-->
    <main class="flex-shrink-0 pt-4 pb-4" style="min-height: 75vh">
        @yield('content')
    </main>
<!--</div>-->
@include('layouts.footer')
<!-- Scripts -->
@stack('scripts')

</body>
</html>
