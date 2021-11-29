@extends('layouts.app')

@section('content')
<div class="container-lg">
<!--aloldal fejléc-->
    <div class="row align-items-center mb-2">
        <div class="col text-md-start mb-3">
            <h1><div class="display-4">{{__('My Prizes')}}</div></h1>
        </div>
    </div>
<!--nyeremények-->
    <div class="row">
        <div class="col-ld-9">
            @if(!$eventPrizes)
                <div class="lead">
                    {{__('No prizes yet!')}}
                </div>
            @endif
            @foreach($eventPrizes as $eventPrize)
                @foreach($eventPrize['prizes'] as $prize)
                    <div class="card bg-tlight text-dark mb-2">
                        <div class="card-body p-1">
                            <div class="row g-0 justify-content-center align-items-center">
                                <div class="col-sm-12 col-lg-9">
                                    <a class="h4 card-title m-2 fw-bold" href="{{route('event.show',$eventPrize['event']->id)}}">
                                        {{$prize->prize_title}}
                                    </a>
                                    <p class="card-text lead m-2">
                                        {{$eventPrize['event']->title}}
                                    </p>
                                    <p class="card-text m-2">
                                        {{$prize->prize_description}}
                                    </p>
                                </div>
                                <div class="col-sm-12 col-lg-3">
                                    <img class="img-thumbnail mx-auto float-sm-start float-lg-end" src="{{asset('uploads/events/'.$prize->prize_img_url)}}" alt="{{$prize->prize_title}}" title="{{$prize->prize_title}}" width="250">
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