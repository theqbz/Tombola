@extends('layouts.app')

@section('content')

    <!--üdvözlő üzenet-->
    <div class="container">
        <p class="display-3 text-dark">{{$user->first_name . " " . $user->last_name}}</p>
        <p class="lead text-dark">A Ticketto rendszere üdvözöl!</p>
    </div>

    <!--dolgok gyors elérése-->
    <div class="container">
        <div class="row">

            <div id="tickets" class="col-sm-12 col-lg-4 p-3 bg-dark">
                <p class="h2 p-1 text-light fw-bold">Szelvényeim</p>
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
                    <a class="btn btn-primary mt-3" href="{{route('mytickets')}}">Összes Tombola</a>
                @else
                    <h2>Nincsenek még szelvények</h2>
            @endif


            <!--szelvény: lejárt, nyert
                <div class="card m-1 bg-info text-white">
                    <div class="card-body">
                        <div class="row g-0 justify-content-center align-items-center">
                            <div class="col-sm-12 col-lg-8">
                                <p class="h5 card-title">Nyertes játék szelvénye</p>
                                <p class="card-text">2021. november 6. 18:00</p>
                            </div>
                            <div class="col-sm-12 col-lg-4">
                                <p class="col-sm-12 col-lg-4">P12</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!--szelvény: rövidesen
                <div class="card m-1 bg-danger text-white">
                    <div class="card-body">
                        <div class="row g-0 justify-content-center align-items-center">
                            <div class="col-sm-12 col-lg-8">
                                <p class="h5 card-title">Hamarosan esedékes játék szelvénye</p>
                                <p class="card-text">2021. november 15.</p>
                            </div>
                            <div class="col-sm-12 col-lg-4 align-items-center">
                                <p class="h1 card-title fw-bold text-lg-end">Z05</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!--szelvény: később
                <div class="card m-1 bg-success text-white">
                    <div class="card-body">
                        <div class="row g-0 justify-content-center align-items-center">
                            <div class="col-sm-12 col-lg-8">
                                <p class="h5 card-title">Későbbi játék szelvénye</p>
                                <p class="card-text">2022. január 16. 19:30</p>
                            </div>
                            <div class="col-sm-12 col-lg-4">
                                <p class="h1 card-title fw-bold text-lg-end">05</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!--szelvény: lejárt, nem nyert
                <div class="card m-1 bg-light text-dark">
                    <div class="card-body">
                        <div class="row g-0 justify-content-center align-items-center">
                            <div class="col-sm-12 col-lg-8">
                                <p class="h5 card-title">Lejárt játék nyeretlen szelvénye</p>
                                <p class="card-text">2021. augusztus 20. 21:00</p>
                            </div>
                            <div class="col-sm-12 col-lg-4">
                                <p class="h1 card-title fw-bold text-lg-end">S05</p>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>

            <div id="events" class="col-sm-12 col-lg-4 p-3 bg-dark">
                <p class="h2 p-1 text-light fw-bold">Játékok</p>
                @php
                    $events = array();
                    if(count($user->getOwnEvents('active',0,5))) {
                        $events = $user->getOwnEvents('active',0,5);
                        if(($eventcount = 5- count($events)) > 0 ) {
                            $events = array_unique(array_merge($events,$user->getOwnEvents('all',0,($eventcount))));
                        }
                    }else {
                         $events = $user->getOwnEvents('all',0,5);
                    }
                @endphp
                @if(count($events))
                    @foreach ($events as $event)
                        <div class="card m-1 text-dark bg-{{$event->getColor()}}">
                            <div class="card-body">
                                <div class="row g-0 justify-content-center align-items-center">
                                    <div class="col-sm-12 col-lg-8">
                                        <p class="h5 card-title"><a
                                                    href="{{route('event.show',$event->id)}}">
                                                {{$event->title}}
                                            </a></p>
                                        <p class="card-text">{{$event->dt_end->formatLocalized('%Y. %b. %-e. %A, %H:%M')}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <h2>Még egy eseményen sem vesz részt</h2>
            @endif

            <!--játék: rövidesen
                <div class="card m-1 border border-5 border-danger bg-light text-dark">
                    <div class="card-body">
                        <div class="row g-0 justify-content-center align-items-center">
                            <div class="col-sm-12 col-lg-8">
                                <p class="h5 card-title">Hamarosan esedékes játék</p>
                                <p class="card-text">2021. november 15.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!--játék: később
                <div class="card m-1 border border-5 border-success bg-light text-dark">
                    <div class="card-body">
                        <div class="row g-0 justify-content-center align-items-center">
                            <div class="col-sm-12 col-lg-8">
                                <p class="h5 card-title">Későbbi játék</p>
                                <p class="card-text">2022. január 16. 19:30</p>
                            </div>
                        </div>
                    </div>
                </div>-->
            </div>

            <div id="prizes" class="col-sm-12 col-lg-4 p-3 bg-dark">
                <p class="h2 p-1 text-light fw-bold">Nyereményeim</p>
                @if(count($eventPrizes = $user->listPrizes()))
                    @foreach($eventPrizes as $eventPrize)
                        @foreach($eventPrize['prizes'] as $prize)
                            <div class="card bg-tlight text-dark mb-2">
                                <div class="card-body p-1">
                                    <div class="row g-0 justify-content-center align-items-center">
                                        <div class="col-sm-12 col-lg-9">
                                            <a class="h4 card-title m-2 fw-bold"
                                               href="{{route('event.show',$eventPrize['event']->id)}}">
                                                {{$prize->prize_title}}
                                            </a>
                                            <p class="card-text lead m-2">
                                                {{$eventPrize['event']->title}}
                                            </p>

                                        </div>
                                        <div class="col-sm-12 col-lg-3">
                                            <img class="img-thumbnail mx-auto float-sm-start float-lg-end"
                                                 src="{{asset('uploads/events/'.$prize->prize_img_url)}}"
                                                 alt="{{$prize->prize_title}}" title="{{$prize->prize_title}}"
                                                 width="250">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                @else
                    <h2>Még nincs egy nyeremény sem</h2>
                @endif
            </div>

        </div>
    </div>

@endsection
<!-- Scripts -->
@stack('scripts')
