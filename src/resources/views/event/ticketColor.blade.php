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
                        <p>Válasszon tombola színt!</p>
                        <div class="box-access">
                            {{Form::model($event,array('route' => array('event.addcolor')))}}
                            {{Form::hidden('id',$event->id)}}
                            {{Form::hidden('uid',$uid)}}
                            {{Form::radioList(['id'=>'color','name'=>'color','label'=>__('Colors'),'radios'=>$event->getAvailableColors(),'inline'=>true])}}
                            {{Form::submit(__('Next'),array('class'=>'btn btn-primary'))}}
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
