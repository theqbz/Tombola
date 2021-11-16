@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6"><h1>{{$event->title}}</h1></div>
                            <div class="col-md-6 text-right">@if(Auth::user()->isEditor($event->id)) <a class="btn btn-primary" href="{{route("event.edit",[$event->id])}}">{{__('Edit')}}</a> @endif</div>
                        </div>
                    </div>
                    <div class="card-body">
                        {!! $event->description !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
