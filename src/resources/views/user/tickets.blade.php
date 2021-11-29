@extends('layouts.app')

@section('content')
<div class="container-lg">
<!--aloldal fejléc-->
    <div class="row align-middle mb-2">
        <div class="col text-md-start">
            <h1><div class="display-4">Szelvényeim</div></h1>
        </div>
        <div class="col text-md-end">
            <div class="dropdown">
                <button class="btn btn-lg btn-tgray dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    {{__($status)}}
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li>
                        <a class="dropdown-item" href="{{route('mytickets',['status'=>'active'])}}">Érvényes szelvények</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{route('mytickets',['status'=>'passive'])}}">Lejárt szelvények</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">Nyertes szelvények</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
<!--szelvények-->
    <div class="row">
        <div class="col-ld-9">
            @if(!$eventTickets)
                <div class="lead">
                    {{__('No tickets yet!')}}
                </div>
            @endif
            @foreach($eventTickets as $eventTicket)
                @foreach($eventTicket['tickets'] as $ticket)
                    <div class="card bg-{{$ticket->getColor}} text-dark mb-2">
                        <div class="card-body p-1">
                            <div class="row g-0 justify-content-center align-items-center">
                                <div class="col-sm-12 col-lg-8">
                                    <a class="h4 card-title m-2 fw-bold" href="{{route('event.show',$eventTicket['event']->id)}}">
                                        {{$eventTicket['event']->title}}
                                    </a>
                                    <p class="card-text m-2">{{$eventTicket['event']->dt_end->format('Y. F j. l - h:i')}}</p>
                                </div>
                                <div class="col-sm-12 col-lg-4 border-start border-2">
                                    <p class="h3 card-title fw-bold text-center">{{$ticket->value}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>
</div>

@endsection