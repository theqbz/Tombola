@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Szelvényeim - {{__($status)}}</h2>
            </div>
            <div class="card-body border-1">

                <div class="row">
                    <div class="col-md-12 mb-5">
                    <a class="btn btn-secondary" href="{{route('mytickets',['status'=>'active'])}}">{{__('Active events')}}</a>
                    <a class="btn btn-danger" href="{{route('mytickets',['status'=>'passive'])}}">{{__('Passive events')}}</a>
                    </div>
                    <div class="col-md-6">
                        @if(!$eventTickets)
                            <h2>{{__('No tickets yet!')}}</h2>
                            @endif
                @foreach($eventTickets as $eventTicket)
                    @foreach($eventTicket['tickets'] as $ticket)
                        <div class="row no-gutters mt-2 mb-2 p-2 bg-{{$ticket->getColor}}" style="border:1px solid;border-radius: 5px">
                            <div class="col-md-9 ">
                                <a href="{{route('event.show',$eventTicket['event']->id)}}"><strong>{{$eventTicket['event']->title}}</strong></a>
                                <p class="mt-2">{{$eventTicket['event']->dt_end->format('Y-m-d h:i')}}</p>
                            </div>
                            <div class="col-md-3 d-flex align-items-center justify-content-center" style="border-left:1px dashed">
                                <h3>
                                    {{$ticket->value}}
                                </h3>
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