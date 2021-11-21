@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6"><h1>{{$event->title}}</h1></div>
                            <div class="col-md-6 text-right">
                                @if(Auth::user() && Auth::user()->isEditor($event->id))
                                    <a class="btn btn-primary"
                                       href="{{route("event.edit",[$event->id])}}">{{__('Edit')}}</a>
                                    <a class="btn btn-dark"
                                       href="{{route("event.ticket",[$event->id])}}">{{__('Ticket')}}</a>
                                @endif</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                {!! $event->description !!}
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    @if($event->isAvailable())
                                    <div class="card-body">
                                        @if($event->hasMoreTickets() && ($event->is_public || !$event->auto_ticket ) && !(Auth::user()->isEditor($event->id)))
                                            {{Form::model($event, array('route' => array('event.addticket', ['id'=>$event->id]),'enctype'=>"multipart/form-data"))}}
                                            <input type="hidden" name="hash" id="hash" value="{{Auth::user()->hash}}">
                                            {{Form::submit(__('Give me a ticket'),array('class'=>'btn btn-primary'))}}
                                            {{ Form::close() }}
                                            <hr>
                                        @endif
                                        <h2>{{__('End Date')}}</h2>
                                        <p>{{$event->getEndDate()}}</p>
                                    </div>
                                    @endif
                                    <div class="card-body">
                                        <h2>{{__('Prizes')}}</h2>
                                        @foreach($event->prizes->all() as $prize)
                                            <div class="row no-gutters border border-dark">
                                                <div class="col-md-3" style="display: flex">
                                                    <img class="img-fluid" src="{{$prize->getImageUrl()}}"
                                                         alt="{{$prize->title}}" style="object-fit: cover">
                                                </div>
                                                <div class="col-md-9 pl-2">
                                                    <p><strong>{{$prize->prize_title}}</strong></p>
                                                    <p>{{$prize->prize_description}}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
