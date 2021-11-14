@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="container">
            <div class="row">
            @php
                @endphp
            @foreach ($events as $event)
                <div class="card m-1" style="width: 18rem;">
                    <img class="card-img-top" src="{{asset('assets/mockimage.svg')}}" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title"> {{$event->title}}</h5>
                        <p class="card-text"> {!! $event->getDescriptionShort() !!}</p>
                        <a href="{{route('event.show',['id'=>$event->id])}}" class="btn btn-primary">Részletek</a>
                    </div>
                </div>

            @endforeach
        </div>
        </div>
    </div>
@endsection
