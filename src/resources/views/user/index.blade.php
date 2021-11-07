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
                                    <a class="btn btn-primary" href="{{route('profile.edit')}}">
                                        {{ __('Edit') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <p class="col-md-4 col-form-label text-md-right">{{__('Email')}}</p>
                                <div class="col-md-6">
                                    <p class="badge badge-secondary p-2" style="font-size: 16px;">{{$user->email}}</p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <p class="col-md-4 col-form-label text-md-right">{{__('First Name')}}</p>
                                <div class="col-md-6">
                                    <p class="badge badge-secondary p-2" style="font-size: 16px;">{{$user->first_name}}</p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <p class="col-md-4 col-form-label text-md-right">{{__('Last Name')}}</p>
                                <div class="col-md-6">
                                    <p class="badge badge-secondary p-2" style="font-size: 16px;">{{$user->last_name}}</p>
                                </div>
                            </div>


                            <div class="form-group row">
                                <p class="col-md-4 col-form-label text-md-right">{{__('Address')}}</p>
                                <div class="col-md-6">
                                    <p class="badge badge-secondary p-2" style="font-size: 16px;">{{$user->address}}</p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <p class="col-md-4 col-form-label text-md-right">{{__('Date Of Birth')}}</p>
                                <div class="col-md-6">
                                    <p class="badge badge-secondary p-2" style="font-size: 16px;">{{$user->date_of_birth }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

@endsection
