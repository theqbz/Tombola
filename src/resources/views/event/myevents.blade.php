@extends('layouts.app')
<style>
    .card-img-top {
        max-width: 200px;
        margin: auto;
    }

    .card-body {
        border-top: 1px solid #cececece;
    }

</style>
@section('content')
    <div class="container">
        <div class="row">
            @foreach ($events as $event)
                <div class="col-md-4 mb-4">
                    <div class="card bg-light">
                        <img class="card-img-top mb-2 mt-2" src="{{asset('assets/logo.svg')}}" alt="Card image cap">
                        <div class="card-body bg-white">
                            <h5 class="card-title"> {{$event->title}}</h5>
                            <p class="card-text"> {!! $event->getDescriptionShort() !!}</p>
                            <a href="{{route('event.show',['id'=>$event->id])}}"
                               class="btn btn-primary">{{__('More')}}</a>
                            <a href="{{route('event.edit',['id'=>$event->id])}}"
                               class="btn btn-primary">{{__('Edit')}}</a>
                            @if($event->hasMoreTickets())
                                <a class="btn btn-dark"
                                   href="{{route("event.ticket",[$event->id])}}">{{__('Ticket')}}</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @isset($passiveEvents)
        <hr>
        <div class="container">
            <h2>Lejárt Események</h2>
            <div class="row">
                @foreach ($passiveEvents as $event)
                    <div class="col-md-4 mb-4">
                        <div class="card bg-expired">
                            <img class="card-img-top mb-2 mt-2" src="{{asset('assets/logo.svg')}}" alt="Card image cap">
                            <div class="card-body">
                                <h5 class="card-title"> {{$event->title}}</h5>
                                <p class="card-text"> {!! $event->getDescriptionShort() !!}</p>
                                <a href="{{route('event.show',['id'=>$event->id])}}"
                                   class="btn btn-primary">{{__('More')}}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endisset

@endsection
