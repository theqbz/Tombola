@extends('admin.main')

@section('content')
    <div class="container-lg">
        <!--aloldal fejléc-->
        <div class="row align-items-center mb-2">
            <div class="col text-md-start mb-3">
                <h1>
                    <div class="display-4">{{__('Events')}}</div>
                </h1>
            </div>
        </div>
        <!--nyeremények-->
        <style>
            table, th, td {
                border: 1px solid black;
            }

            table.center {
                margin-left: auto;
                margin-right: auto;
            }
        </style>
        <div class="row">
            @foreach($events as $event)
                <div>
                    <h2>{{$event['event']->title}}<span class="bg-active">{{$event['event']->getEndDate()}}</span> <span
                                class="bg-danger text-white">{{$event['event']->id}}</span></h2>
                    @isset($event['tickets'])
                        @foreach($event['tickets'] as $email => $tickets)
                            <div class="card p-1 bg-expired mt-2">
                                {{$email}}
                                @foreach($tickets as $ticket)
                                    <div class="card p-1 bg-info">
                                        <p>{{$ticket->color .'-'. $ticket->value}}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    @endisset
                    @if(!isset($event['tickets']))
                        <div class="card p-1 bg-danger mt-2">
                            <p>Nincsenek tombolák</p>
                        </div>
                    @endif
                    <hr>
                </div>
            @endforeach
        </div>
    </div>

@endsection