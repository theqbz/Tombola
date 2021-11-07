@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    @include('user.left');
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h2>{{__("Profile")}}</h2>
                                </div>
                                <div class="col-md-6 text-right">
                                    <a class="btn btn-danger" href="{{route('profile.index')}}">
                                        {{ __('Mégsem') }}
                                    </a>
                                </div>
                            </div>
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

                                    {{Form::text('date_of_birth', old('date_of_birth'),array('class'=>"form-control"))}}

                                </div>
                                @error('date_of_birth')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>


                            <div class="form-group row text-md-right">
                                <div class="col-md-6 offset-md-4">
                                    {{Form::submit(__('Save'),array('class'=>'btn btn-primary'))}}
                                </div>
                            </div>
                            {{ Form::close() }}

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

@endsection
