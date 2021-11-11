@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verify Your Email Address') }}</div>

                <div class="card-body">
                    @isset ($resent)
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh access link was sent to your email.') }}
                        </div>
                    @endif

                    {{ __('We sent the access details via email.') }}
                    {{ __('If you did not receive the email') }},
                    <form class="d-inline" method="POST" action="{{ route('resendtemp') }}">
                        @csrf
                        @isset($id)
                            <input name="id" type="hidden" value="{{$id}}">
                        @endisset
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection