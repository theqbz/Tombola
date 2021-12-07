@extends('layouts.app')



@section('content')
    <style>
        .invalid-feedback {
            display: block
        }
    </style>
    <div class="container-lg">

        <!--aloldal fejléc-->

        <div class="row align-items-top mb-2">

            <div class="col text-start mb-3">

                <h1>

                    <div class="display-4">{{__('Edit Profile')}}</div>

                </h1>

            </div>

        </div>

        <!-- adatlap szerkesztés-->

        <div class="row">

            <div class="col-sm-4">

                @include('user.left')

            </div>

            <div class="col-sm-8">

                <div class="card">

                    <div class="card-header">

                        <div class="h2 lead">{{__("Profile")}}</div>

                    </div>

                    <div class="card-body">

                        @isset($error)

                            <div class="alert alert-danger">

                                {{ $error}}

                            </div>

                        @endif

                        @if(session()->has('success'))

                            <div class="alert alert-success">

                                {{ session()->get('success') }}

                            </div>

                        @endif

                        {{Form::model($user, array('route' => array('profile.update', $user->id)))}}

                        <div class="form-group row">

                            {{Form::label('email', __('Email'), array('class' => 'col-md-4 col-form-label text-md-right'))}}

                            <div class="col-md-6">


                                {{Form::text('email', old('email'),array('class'=>"form-control"))}}


                            </div>

                            @error('email')

                            <span class="invalid-feedback" role="alert">

                            <strong>{{ $message }}</strong>

                        </span>

                            @enderror

                        </div>


                        <div class="form-group row">

                            {{Form::label('first_name', __('First Name'), array('class' => 'col-md-4 col-form-label text-md-right'))}}

                            <div class="col-md-6">

                                {{Form::text('first_name', old('first_name'),array('class'=>"form-control"))}}

                            </div>

                            @error('first_name')

                            <span class="invalid-feedback" role="alert">

                            <strong>{{ $message }}</strong>

                        </span>

                            @enderror

                        </div>


                        <div class="form-group row">

                            {{Form::label('last_name', __('Last Name'), array('class' => 'col-md-4 col-form-label text-md-right'))}}

                            <div class="col-md-6">

                                {{Form::text('last_name', old('last_name'),array('class'=>"form-control"))}}

                            </div>

                            @error('last_name')

                            <span class="invalid-feedback" role="alert">

                            <strong>{{ $message }}</strong>

                        </span>

                            @enderror

                        </div>


                        <div class="form-group row">

                            {{Form::label('address', __('Address'), array('class' => 'col-md-4 col-form-label text-md-right'))}}

                            <div class="col-md-6">

                                {{Form::text('address', old('address'),array('class'=>"form-control"))}}

                            </div>

                            @error('address')

                            <span class="invalid-feedback" role="alert">

                            <strong>{{ $message }}</strong>

                        </span>

                            @enderror

                        </div>


                        <div class="form-group row">

                            {{Form::label('date_of_birth', __('Date Of Birth'), array('class' => 'col-md-4 col-form-label text-md-right'))}}

                            <div class="col-md-6">


                                @php

                                    $date = $user->date_of_birth;

                                    if($date){

                                        $date = new DateTime($date);

                                        $date = $date->format('Y-m-d');

                                    }else {

                                        $date = "ÉÉÉÉ/HH/NN";

                                    }

                                @endphp

                                {{Form::datePicker(['id'=>'date_of_birth','value'=>$date,'name'=>'date_of_birth','placeholder'=>true])}}
                                @error('date_of_birth')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong> </span>
                                @enderror
                            </div>


                        </div>

                        <div class="form-group row text-md-end">

                            <div class="col">

                                <a class="btn btn-danger" href="{{route('profile.index')}}">

                                    {{ __('Cancel') }}

                                </a>

                                {{Form::submit(__('Save'),array('class'=>'btn btn-primary'))}}

                            </div>

                        </div>

                        {{ Form::close() }}

                    </div>

                </div>

            </div>

        </div>



@endsection

