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
                        <p>Válasszon számot!</p>
                        <div class="box-access">
                            {{Form::model($event,array('route' => array('event.addticket')))}}
                            {{Form::hidden('id',$event->id)}}
                            {{Form::hidden('uid',$uid)}}
                            @isset($color)
                            {{Form::hidden('color',$color)}}
                            @endisset
                            @if($tickets != 0)
                                {{Form::radioList(['id'=>'number','name'=>'number','label'=>__('Numbers'),'radios'=>$tickets,'inline'=>true])}}
                            @endif
                            @if($tickets == 0)
                                {{Form::label('number',__('Write a ticket value'))}}
                                {{Form::number('number',1,array('class'=>'form-control','min'=>0))}}
                            @endif
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
