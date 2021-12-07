@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6"><h1>{{$event->title}} - {{__('Ticket')}}</h1></div>
                            <div class="col-md-6 text-right"></div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session()->has('success'))
                            <div class="alert alert-success">
                                {{ session()->get('success') }}
                            </div>
                        @endif
                        @error('error')
                        <div class="alert alert-danger">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                        @if($event->hasMoreTickets())
                            <p>Olvassa be a játékos qr kódját, vagy írja be az azonosítóját!</p>
                            @php
                                $text = 'Add Ticket';
                                $route = 'event.addticket';
                                    if(!$event->auto_ticket) {
                                        $text = 'Next';
                                        if($event->hasMultipleColors()) {
                                            $route = 'event.color';
                                        }
                                    }
                            @endphp
                            <div class="box-access m-auto col-md-6 offset-md-3">
                                {{Form::model($event, array('route' => array($route),'enctype'=>"multipart/form-data"))}}
                                {{Form::QrCodeReader(['name'=>'hash','id'=>'hash','label'=>__('Access code')])}}
                                <div class="form-group text-md-right mt-2">
                                    {{Form::hidden('id',$event->id)}}
                                    {{Form::submit(__($text),array('class'=>'btn btn-primary'))}}
                                </div>
                                {{ Form::close() }}
                            </div>
                        @endif
                        @if(!$event->hasMoreTickets())
                            <h2>A tombolák elfogytak</h2>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
