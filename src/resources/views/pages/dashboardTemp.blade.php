<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow,nosnippet">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Ticketto') }}</title>

    <script src="{{ asset('js/app.js') }}" defer></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
            crossorigin="anonymous"></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/ticketto.css') }}" rel="stylesheet">
</head>
<body class="d-flex flex-column h-100">
<header>
    @include('layouts.header')
</header>
<main class="flex-shrink-0 pt-4 pb-4">
    <div class="container-lg">
        <p class="small bg-ticketto text-dark p-1"><b>Jelenleg ideiglenes felhasználói státuszban vagy.</b> Játszani így
            is tudsz, de az oldal minden funkciójának eléréséhez regisztrálnod kell.<br>
            Tombolát az alábbi QR-kóddal vagy profil azonosítóval kaphatsz; a csatlakozás játékhoz gombbal
            regisztrálhatsz a meghirdetett játékokra.</p>
        <p class="display-3 text-dark">{{$user->email}}</p>
        <div class="row">
            <div class="col-md-3">
                @include('user.left')
                <div class="d-grid gap-2 mt-3">
                    <a class="btn btn-primary justify-content-center" href="{{route('connect.temp',$user->hash)}}">Csatlakozás
                        játékhoz</a>
                </div>
            </div>
            <div class="col-md-9">
                <div id="tickets" class="">
                    <p class="h2 p-1 text-dark fw-bold">Szelvényeim</p>
                    @php
                        $tickets = array();
                        if(count($user->listTickets('active',5))) {
                            $tickets = $user->listTickets('active',5);
                            if(($ticketcount = 4- count($tickets)) > 0 ) {
                                $tickets = array_merge($tickets,$user->listTickets('passive',($ticketcount)));
                            }
                        }else {
                            $tickets = $user->listTickets('all',5);
                        }
                    @endphp
                    @if(count($tickets))
                        @foreach($tickets as $eventTicket)
                            @foreach($eventTicket['tickets'] as $ticket)
                                <div class="card bg-{{$ticket->getColor()}} m-1">
                                    <div class="card-body">
                                        <div class="row g-0 justify-content-center align-items-center">
                                            <div class="col-sm-12 col-lg-8">
                                                <a class="h5 card-title"
                                                   href="{{route('event.show',$eventTicket['event']->id)}}">
                                                    {{$eventTicket['event']->title}}
                                                </a>
                                                <p class="card-text">{{$eventTicket['event']->dt_end->formatLocalized('%Y. %b. %-e. %A, %H:%M')}}</p>
                                            </div>
                                            <div class="col-sm-12 col-lg-4">
                                                <p class="h1 card-title fw-bold text-lg-end">{{strtoupper(substr($ticket->getColorName(), 0, 1))}}{{$ticket->value}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    @else
                        <h2>Nincsenek még szelvények</h2>
                    @endif


                </div>
            </div>
        </div>
    </div>
</main>

<footer class="footer mt-auto bg-dark border-top border-ticketto border-5">
    @include('layouts.footer')
</footer>
<!-- Scripts -->
@stack('scripts')

</body>
</html>

