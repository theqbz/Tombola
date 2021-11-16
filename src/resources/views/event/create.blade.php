@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="container">
            <div class="card">
                {{Form::open(array('route' => 'event.store','enctype'=>"multipart/form-data"))}}
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h2>{{__("Create event")}}</h2>
                        </div>
                        <div class="col-md-6 text-md-right">
                            {{Form::submit(__('Save'),array('class'=>'btn btn-primary'))}}
                        </div>
                    </div>

                </div>

                <div class="card-body">
                    @if(session()->has('error'))
                        <div class="alert d-block alert-danger"> {{ session()->get('error') }}</div>
                    @endif

                    <div class="form-group">
                        {{Form::label('title', __('Title'), array('class' => ' col-form-label text-md-right'))}}
                        {{Form::text('title', old('title'),array('class'=>"form-control"))}}
                        @error('title')
                        <span class="alert d-block alert-danger" role="alert"><strong>{{ $message }}</strong> </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        {{Form::label('location', __('Location'), array('class' => ' col-form-label text-md-right'))}}
                        {{Form::text('location', old('location'),array('class'=>"form-control"))}}
                        @error('location')
                        <span class="alert d-block alert-danger" role="alert"><strong>{{ $message }}</strong> </span>
                        @enderror
                    </div>
                    {{ Form::WyswygEditor(['id'=>'description','value'=>old('description'),'name'=>'description','label'=>__('Description')]) }}
                    @error('description')
                    <span class="alert d-block alert-danger" role="alert"><strong>{{ $message }}</strong> </span>
                    @enderror

                    <div class="row">
                        <div class="col-md-6">
                            {{Form::datePicker(['id'=>'dt_start','value'=>old('dt_start'),'name'=>'dt_start','label'=>__('Start Date'),'needTime'=>true,'valueTime'=>old('dt_start_time')])}}
                            @error('dt_start_full')
                            <span class="alert alert-danger d-block" role="alert"><strong>{{ $message }}</strong> </span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            {{Form::datePicker(['id'=>'dt_end','value'=>old('dt_end'),'name'=>'dt_end','label'=>__('End Date'),'needTime'=>true,'valueTime'=>old('dt_end_time')])}}
                            @error('dt_end_full')
                            <span class="alert alert-danger d-block" role="alert"><strong>{{ $message }}</strong> </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            {{Form::radioList(['id'=>'is_public','name'=>'is_public','label'=>__('Event Type'),'radios'=>array(__('Public')=>1,__('Private')=>0),'checked'=>1])}}
                        </div>
                        <div class="col-md-2">
                            {{Form::radioList(['id'=>'auto_ticket','name'=>'auto_ticket','label'=>__('Draw type'),'radios'=>array(__('Auto')=>1,__('Manual')=>0),'checked'=>1])}}
                        </div>
                        <div class="col-md-2 d-none">
                            {{Form::radioList(['id'=>'chosable_color','name'=>'chosable_color','label'=>__('Multiple colors'),'radios'=>array(__('Yes')=>1,__('No')=>0),'checked'=>0])}}
                        </div>
                    </div>
                    <p class="mt-5">{{__('Prizes')}}</p>
                    <div id="prizes" class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-3">{{__('Title')}}</div>
                                <div class="col-md-3">{{__('Description')}}</div>
                                <div class="col-md-6">{{__('Image')}}</div>
                                <div class="col-md-3 form-group">
                                    <input type="text" class="form-control" id="prize_title_add" name="prize_title_add">
                                </div>
                                <div class="col-md-3 form-group">
                                    <textarea id="prize_description_add" class="form-control" name="prize_description_add"></textarea>
                                </div>
                                <div class="col-md-4 form-group">
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="prize_image_add" id="prize_image_add" lang="hu_HU">
                                            <label class="custom-file-label" for="prize_image_add" data-browse="asd" aria-describedby="inputGroupFileAddon02">{{__('Choose')}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group text-md-right col-md-2">
                                    <a href="#" id="add_prize" class="btn btn-secondary">{{__('Add')}}</a>
                                </div>
                                <span id="prize_error" class="invalid-feedback was-validated" role="alert"><strong></strong> </span>
                            </div>
                        </div>
                        @if(session()->has('images'))
                            @foreach (session()->get('images') as $image)
                                <div class="prize_item card-body row col-md-12">
                                    <div class="col-md-3">
                                        <p>{{$image['title']}}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p>{{$image['description']}}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <img style="max-width: 100px;" id="prize" src="{{asset('temp/events/'.$image['image'])}}" alt="{{$image['title']}}" title="{{$image['title']}}" width="250">
                                    </div>
                                    <div class="d-none">
                                        <input type="text" name="prize_item_title_{{ $loop->index }}" value="{{$image['title']}}">
                                        <input type="text" name="prize_item_description_{{ $loop->index }}" value="{{$image['description']}}">
                                        <input type="text" name="prize_item_image_{{ $loop->index }}" value="{{$image['image']}}">
                                    </div>
                                </div>
                            @endforeach
                            <div class="d-none">
                                <input name="update" value="1">
                            </div>
                        @endif
                        @error('prize')
                        <span class="alert alert-danger" role="alert"><strong>{{ $message }}</strong> </span>
                        @enderror
                    </div>
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
    <script type="text/javascript">
        $(document).ready(function () {
            bsCustomFileInput.init();
            $('#add_prize').on('click', function (e) {
                const idx = $('.prize_item').length;
                e.preventDefault();
                let img = null;
                let errorMsg = $("<ul></ul>");
                let isError = false;
                const file = $('#prize_image_add')[0].files[0];
                const title = $('#prize_title_add').val();
                const description = $('#prize_description_add').val();
                if (!file) {
                    $('<li>Kép feltöltése kötelező!</li>').appendTo(errorMsg);
                    isError = true;
                }
                if (!title.length) {
                    $('<li>Cím kitöltése kötelező!</li>').appendTo(errorMsg);
                    isError = true;
                }
                if (!description.length) {
                    $('<li>Leírás kitöltése kötelező!</li>').appendTo(errorMsg);
                    isError = true;
                }
                if (isError) {
                    $('#prize_error').html(errorMsg);
                    $('#prize_error').show();
                } else {
                    const reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = function (e) {

                        img = $('<img />').attr({
                            'id': 'prize',
                            'src': e.target.result,
                            'alt': title,
                            'title': title,
                            'width': 100
                        });

                        $('#prize_error').hide();
                        const container = $('<div class="prize_item card-body row col-md-12"></div>');
                        const titleGrid = $('<div class="col-md-3"><p>' + title + '</p><div class="d-none"><input name="prize_title_' + idx + '" value="' + title + '"></div></div>');
                        titleGrid.appendTo(container);

                        const descGrid = $('<div class="col-md-3"><p>' + description + '</p><div class="d-none"><input name="prize_description_' + idx + '" value="' + description + '"></div></div>');
                        descGrid.appendTo(container);


                        const fileInputCopy = $('#prize_image_add').clone();
                        fileInputCopy.attr('name', 'prize_image_' + $('.prize_item').length);
                        fileInputCopy.removeAttr('id');

                        const imageGrid = $('<div class="col-md-6"></div>');
                        img.appendTo(imageGrid);

                        const hideContainer = $('<div class="d-none"></div>');
                        fileInputCopy.appendTo(hideContainer);
                        hideContainer.appendTo(imageGrid);

                        imageGrid.appendTo(container);

                        $('#prizes').append(container)

                        $('#prizes').append($('<hr>'));

                        $('#prize_image_add').next('label').html('{{__('Choose')}}');
                        ;
                        $('#prize_title_add').val("");
                        $('#prize_description_add').val("");
                    };
                }



                {{--let formData = new FormData();--}}
                {{--formData.append('title',$('#prize_title_add').val())--}}
                {{--formData.append('image',$('#prize_image_add')[0].files[0],'prize')--}}
                {{--$.ajax({--}}
                {{--    method: "POST",--}}
                {{--    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},--}}
                {{--    url: "{{route('prize.add')}}",--}}
                {{--    data: formData,--}}
                {{--    processData: false,--}}
                {{--    contentType: false,--}}
                {{--    success:function(data){--}}
                {{--        console.log(data);--}}
                {{--    }--}}
                {{--})--}}

            })

        })
    </script>
@endpush
