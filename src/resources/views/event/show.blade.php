@extends('layouts.app')



@section('content')

    <div class="container-lg">

        <!--aloldal fejléc-->

        <div class="row align-items-top mb-2">

            <div class="col-lg-9 text-start mb-3">

                <h1>

                    <div class="display-4">{{$event->title}}</div>

                </h1>

            </div>

            <div class="col-lg-3 pt-3">

                <div class="d-grid gap-2 d-xs-flex justify-content-sm-end">

                    @if(Auth::user() && Auth::user()->isEditor($event->id))



                        @if($event->isAvailable() && $event->hasMoreTickets())

                            <a class="btn btn-primary" href="{{route("event.edit",[$event->id])}}">

                                {{__('Edit')}}

                            </a>

                            <a class="btn btn-ticketto" href="{{route("event.ticket",[$event->id])}}">

                                {{__('Sale Ticket')}}

                            </a>

                        @endif

                    @endif

                    @if($event->isAvailable())

                        @if($event->hasMoreTickets() && ($event->is_public) && !(Auth::user()->isEditor($event->id) && !Auth::user()->hasTicketForEvent($event)))

                            {{Form::model($event, array('route' => array('event.color', ['id'=>$event->id]),'enctype'=>"multipart/form-data"))}}

                            <input type="hidden" name="hash" id="hash" value="{{Auth::user()->hash}}">

                            {{Form::submit(__('Csatlakozom'),array('class'=>'btn btn-primary'))}}

                            {{ Form::close() }}



                        @endif

                    @endif


                </div>

            </div>

        </div>

        <!-- esemény adatai -->

        <div class="row border-top border-light align-items-top p-3">

            <div class="col-md-4">

                <p class="small fw-bold text-uppercase">{{__('Start Date')}}</p>

                <p class="lead">{{$event->getStartDate()}}</p>

            </div>

            <div class="col-md-4">

                <p class="small fw-bold text-uppercase">{{__('Draw Date')}}</p>

                <p class="lead">{{$event->getEndDate()}}</p>

            </div>

            <div class="col-md-2">

                <p class="small fw-bold text-uppercase">{{__('Location')}}</p>

                <p class="lead">{{$event->location}}</p>

            </div>

            @if($event->isAvailable() && $event->hasMoreTickets() && ($event->is_public || !$event->auto_ticket ) && $event->hasLimit())

                <div class="col-md-2 bg-light pt-1 pr-3 pl-3 text-right">

                    <p class="small fw-bold text-uppercase">{{__('Available tickets')}}</p>

                    <p class="lead">{{$event->ticketsLeft()}}</p>

                </div>

            @endif

        </div>

        <!-- esemény leírása és nyeremények-->

        <div class="row border-top border-light align-items-top p-3">

            <div class="col-md-4">

                <p class="small fw-bold text-uppercase">{{__('Description')}}</p>

                {!! $event->description !!}

                <div class="access">

                    <img style="width: 150px" src="{{asset('qrcodes/events/'.$event->id.'.svg')}}" alt="">

                </div>


            </div>


            <div class="col-md-8">

                <p class="small fw-bold text-uppercase">{{__('Prizes')}}</p>

                @foreach($event->prizes->all() as $prize)

                    <div class="card border-light mb-3" style="height: 400px;overflow: hidden">

                        <img class="card-img" src="{{$prize->getImageUrl()}}" alt="{{$prize->title}}"

                             style="object-fit: contain;object-position:50% 50%;">

                        <div class="card-img-overlay">

                            <div class="h4 card-title bg-light fw-bold p-2 m-0" style="opacity: 0.7">

                                {{$prize->prize_title}}

                            </div>

                            <div class="card-text bg-light p-2 m-0" style="opacity: 0.7">

                                {{$prize->prize_description}}

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </div>



@endsection