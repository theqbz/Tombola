@extends('layouts.app')

@section('content')

    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="card">
                {{Form::model($event, array('route' => array('event.update', $event->id),'enctype'=>"multipart/form-data"))}}
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h2>{{__("Edit event")}}</h2>
                        </div>
                        <div class="col-md-6 text-md-right">
                            {{Form::submit(__('Save'),array('class'=>'btn btn-primary'))}}
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

                    <div class="form-group">
                        {{Form::label('title', __('Title'), array('class' => ' col-form-label text-md-right'))}}
                        {{Form::text('title', $event->title,array('class'=>"form-control"))}}
                        @error('title')
                        <span class="alert d-block alert-danger" role="alert"><strong>{{ $message }}</strong> </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        {{Form::label('location', __('Location'), array('class' => ' col-form-label text-md-right'))}}
                        {{Form::text('location', $event->location,array('class'=>"form-control"))}}
                        @error('location')
                        <span class="alert d-block alert-danger" role="alert"><strong>{{ $message }}</strong> </span>
                        @enderror
                    </div>
                    {{ Form::WyswygEditor(['id'=>'description','value'=>$event->description,'name'=>'description','label'=>__('Description')]) }}
                    @error('description')
                    <span class="alert d-block alert-danger" role="alert"><strong>{{ $message }}</strong> </span>
                    @enderror

                    <div class="row">
                        <div class="col-md-6">
                            {{Form::datePicker(['id'=>'dt_start','value'=>$event->dt_start->format('Y-m-d'),'name'=>'dt_start','label'=>__('Start Date'),'needTime'=>true,'valueTime'=>$event->dt_start->format('H:i')])}}
                            @error('dt_start_full')
                            <span class="alert alert-danger d-block"
                                  role="alert"><strong>{{ $message }}</strong> </span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            {{Form::datePicker(['id'=>'dt_end','value'=>$event->dt_end->format('Y-m-d'),'name'=>'dt_end','label'=>__('End Date'),'needTime'=>true,'valueTime'=>$event->dt_end->format('H:i')])}}
                            @error('dt_end_full')
                            <span class="alert alert-danger d-block"
                                  role="alert"><strong>{{ $message }}</strong> </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="rounded bg-expired p-2">
                                <p>{{__('Event Type')}}</p>
                                <strong>{{$event->is_public?__('Public'):__('Private')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="rounded bg-expired p-2">
                                <p>{{__('Draw type')}}</p>
                                <strong>{{$event->auto_ticket?__('Auto'):__('Manual')}}</strong>
                            </div>
                        </div>
                    </div>
                    <p class="mt-5">{{__('Prizes')}}</p>
                    @foreach ($event->prizes->all() as $image)
                        <div class="prize_item card-body row col-md-12">
                            <div class="col-md-3">
                                <p>{{$image->prize_title}}</p>
                            </div>
                            <div class="col-md-3">
                                <p>{{$image->prize_description}}</p>
                            </div>
                            <div class="col-md-6">
                                <img style="max-width: 100px;" id="prize"
                                     src="{{asset('uploads/events/'.$image->prize_img_url)}}"
                                     alt="{{$image->prize_title}}" title="{{$image->prize_title}}" width="250">
                            </div>
                        </div>
                    @endforeach
                    <div class="form-group text-md-right mt-2">
                        {{Form::submit(__('Save'),array('class'=>'btn btn-primary'))}}
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('js/ckeditor/adapters/jquery.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>
    </script>
@endpush
