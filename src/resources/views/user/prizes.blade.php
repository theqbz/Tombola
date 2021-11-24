@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>{{__('My Prizes')}}</h2>
            </div>
            <div class="card-body border-1">

                <div class="row">
                    <div class="col-md-6">
                        @if(!$eventPrizes)
                            <h2>{{__('No prizes yet!')}}</h2>
                            @endif
                            @foreach($eventPrizes as $eventPrize)
                                @foreach($eventPrize['prizes'] as $prize)
                                    <div class="row no-gutters mt-2" style="border:1px solid;border-radius: 5px">
                                        <div class="col-md-8 mb-2 p-3">
                                            <a class="mb-2 d-block" href="{{route('event.show',$eventPrize['event']->id)}}"><strong>{{$eventPrize['event']->title}}</strong></a>
                                            <p>{{$prize->prize_title}}</p>
                                            <p>{{$prize->prize_description}}</p>
                                        </div>
                                        <div class="col-md-4 d-flex" style="border-left:1px dashed">
                                            <img style="object-fit: cover;object-position:left;" class="img-fluid" src="{{asset('uploads/events/'.$prize->prize_img_url)}}" alt="{{$prize->prize_title}}" title="{{$prize->prize_title}}" width="250">
                                        </div>
                                    </div>
                            @endforeach
                            @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection