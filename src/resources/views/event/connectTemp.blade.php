@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6"><h1>Csatlakozás egy eseményhez</h1></div>
                            <div class="col-md-6 text-right"></div>
                        </div>
                    </div>
                    <div class="card-body">
                        @error('error')
                        <div class="alert alert-danger">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                        <p>Olvassa be egy esemény qr kódját!</p>
                        <div class="box-access m-auto col-md-6 offset-md-3">
                            {{Form::open(array('route' => array('qr.temp',$hash),'enctype'=>"multipart/form-data"))}}
                            {{Form::QrCodeReader(['name'=>'hash','id'=>'hash','label'=>__('Access code'),'hiddenField'=>true])}}
                            <div class="form-group text-md-right mt-2">
                                {{Form::submit(__('Connect'),array('class'=>'d-none btn btn-primary'))}}
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
