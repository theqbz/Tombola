@extends('layouts.app')

@section('content')
        <div class="container">
            <div class="row">
            @php
                @endphp
            @foreach ($events as $event)
                <div class="col-md-4 ml-1 mr-1">
                <div class="card" >
                    <img class="card-img-top" src="{{asset('assets/logo.svg')}}" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title"> {{$event->title}}</h5>
                        <p class="card-text"> {!! $event->getDescriptionShort() !!}</p>
                        <a href="{{route('event.show',['id'=>$event->id])}}" class="btn btn-primary">{{__('More')}}</a>
                        <a href="{{route('event.edit',['id'=>$event->id])}}" class="btn btn-primary">{{__('Edit')}}</a>
                        <a class="btn btn-dark" href="{{route("event.ticket",[$event->id])}}">{{__('Ticket')}}</a>
                    </div>
                </div>
                </div>
            @endforeach
        </div>
        </div>
@endsection
