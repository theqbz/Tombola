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
                        @error('error')
                        <div class="alert alert-danger">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                        @if(empty($errors->any()))
                            <p>Olvassa be a játékos qr kódját, vagy írja be az azonosítóját!</p>
                            <div class="box-access m-auto col-md-6 offset-md-3">
                                {{Form::model($event, array('route' => array('event.addticket', ['id'=>$event->id]),'enctype'=>"multipart/form-data"))}}
                                {{Form::QrCodeReader(['name'=>'userhash','id'=>'userhash','label'=>__('Access code')])}}
                                <div class="form-group text-md-right mt-2">
                                    @php
                                        $text = "Add Ticket";
                                            if($event->auto_ticket) {
                                                $text = "Next";
                                            }
                                    @endphp
                                    {{Form::submit(__($text),array('class'=>'btn btn-primary'))}}
                                </div>
                                {{ Form::close() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
