@extends('layouts.app')



@section('content')
    <div class="container-lg">

        <!--aloldal fejléc-->

        <div class="row align-items-center mb-2">

            <div class="col text-md-start mb-3">

                <h1>

                    <div class="display-4">{{__("Create event")}}</div>

                </h1>

            </div>

        </div>

        <!--esemény-létrehozó form-->

        <div class="card bg-light text-dark">

            <!--esemény hozzáadása-->

            <div class="card-header">

                <div class="lead">

                    {{__("Event")}}

                </div>

            </div>

            <div class="card-body">

                {{Form::open(array('route' => 'event.store','enctype'=>"multipart/form-data"))}}

                @if(session()->has('error'))

                    <p class="alert d-block alert-danger">

                        {{ session()->get('error') }}

                    </p>

                @endif


                <div class="form-group">

                    {{Form::label('title', __('Title'), array('class' => ' col-form-label text-md-right'))}}

                    {{Form::text('title', old('title'),array('class'=>"form-control"))}}

                    @error('title')

                    <span class="alert d-block alert-danger fw-bold" role="alert">

                    {{ $message }}

                </span>

                    @enderror

                </div>

                <div class="form-group">

                    {{Form::label('location', __('Location'), array('class' => ' col-form-label text-md-right'))}}

                    {{Form::text('location', old('location'),array('class'=>"form-control"))}}

                    @error('location')

                    <span class="alert d-block alert-danger fw-bold" role="alert">

                    {{ $message }}

                </span>

                    @enderror

                </div>

                {{ Form::WyswygEditor(['id'=>'description','value'=>old('description'),'name'=>'description','label'=>__('Description')]) }}

                @error('description')

                <span class="alert d-block alert-danger" role="alert">

                {{ $message }}

            </span>

                @enderror


                <div class="row">

                    <div class="col-md-6">

                        {{Form::datePicker(['id'=>'dt_start','value'=>old('dt_start'),'name'=>'dt_start','label'=>__('Start Date'),'needTime'=>true,'valueTime'=>old('dt_start_time')])}}

                        @error('dt_start_full')

                        <span class="alert alert-danger d-block" role="alert">{{ $message }} </span>

                        @enderror

                    </div>

                    <div class="col-md-6">

                        {{Form::datePicker(['id'=>'dt_end','value'=>old('dt_end'),'name'=>'dt_end','label'=>__('End Date'),'needTime'=>true,'valueTime'=>old('dt_end_time')])}}

                        @error('dt_end_full')

                        <span class="alert alert-danger d-block" role="alert">{{ $message }} </span>

                        @enderror

                        <span class="alert alert-danger d-none minTimeDiffWarning" role="alert">Az esemény rövidebb lesz mint 30 perc! </span>


                    </div>

                </div>

                <div class="row mb-4">

                    <div class="col-md-3 form-group">
                        <div>
                            {{Form::checkbox('is_limitable',1,old('is_limitable',0),array('class'=>'check-input','min'=>0,'id'=>'is_limitable'))}}
                            <label class="form-check-label" for="flexCheckDefault">
                                {{__('Limit of tickets')}}
                            </label>
                        </div>
                        <div class="box__limit {{(old('is_limitable',0))?'':'d-none'}}">
                            {{Form::number('limit',old('limit',0),array('class'=>'form-control','min'=>0,'max'=>500))}}
                        </div>

                        @error('limit')
                        <span class="alert alert-danger d-block" role="alert">{{ $message }} </span>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        {{Form::radioList(['id'=>'is_public','name'=>'is_public','label'=>__('Event Type'),'radios'=>array(__('Public')=>1,__('Private')=>0),'checked'=>old('is_public',1)])}}
                    </div>

                    <div id="ticket_chose_box_color-check" class="col-md-5">
                        <div>
                            {{Form::checkbox('is_multicolor',1,old('is_multicolor',0),array('class'=>'check-input','min'=>0,'id'=>'is_multicolor'))}}
                            <label class="form-check-label" for="flexCheckDefault">
                                Többszínű
                            </label>
                        </div>
                        <div class="form-group box__color {{(old('is_multicolor',0))?'':'d-none'}}">
                            {{Form::select('colors[]', $colors,old('colors'),array('class' => 'form-control','multiple'=>true))}}
                        </div>
                        @error('multi_colors')
                        <span class="alert alert-danger d-block" role="alert">{{ $message }} </span>
                        @enderror
                    </div>

                </div>

                <div class="card" id="prizes">

                    <div class="card-header">

                        <div class="lead">

                            {{__('Prizes')}}

                        </div>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-3">{{__('Prize name')}}</div>

                            <div class="col-md-3">{{__('Description')}}</div>

                            <div class="col-md-6">{{__('Image')}}</div>

                            <div class="col-md-3 form-group">
                                <input type="text" class="form-control" id="prize_title_add" name="prize_title_add">
                            </div>

                            <div class="col-md-3 form-group">
                                <textarea id="prize_description_add" class="form-control"
                                          name="prize_description_add"></textarea>
                            </div>
                            <div class="col-md-4 form-group">
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="prize_image_add" id="prize_image_add" lang="hu_HU">
                                        <label class="custom-file-label" for="prize_image_add" data-browse="asd"
                                               aria-describedby="inputGroupFileAddon02">{{__('Choose')}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-md-right col-md-2">
                                <a href="#" id="add_prize" class="btn btn-secondary">{{__('Add')}}</a>
                            </div>
                            <span id="prize_error" class="invalid-feedback was-validated" role="alert"> </span>
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
                                    <img style="max-width: 100px;" id="prize"
                                         src="{{asset('temp/events/'.$image['image'])}}" alt="{{$image['title']}}"
                                         title="{{$image['title']}}" width="250">
                                </div>
                                <div class="d-none">
                                    <input type="text" name="prize_item_title_{{ $loop->index }}"
                                           value="{{$image['title']}}">
                                    <input type="text" name="prize_item_description_{{ $loop->index }}"
                                           value="{{$image['description']}}">
                                    <input type="text" name="prize_item_image_{{ $loop->index }}"
                                           value="{{$image['image']}}">
                                </div>
                            </div>
                        @endforeach
                    @endif
                    @error('prize')
                    <span class="alert alert-danger" role="alert">{{ $message }} </span>
                    @enderror

                </div>

                <div class="form-group d-block text-md-right mt-5">

                    {{Form::submit(__('Save event'),array('class'=>'btn btn-primary'))}}

                </div>

                {{ Form::close() }}

            </div>

        </div>

    </div>



@endsection

@push('scripts')

    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

    <script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>

    <script src="{{ asset('js/ckeditor/adapters/jquery.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>

    <script type="text/javascript">

        $(document).ready(function () {


            /*DATE DIFF CHECKER*/
            $('#dt_end').on('change', checkDateDiff);
            $('#dt_end_time').on('change', checkDateDiff);

            function checkDateDiff() {
                $('.minTimeDiffWarning').addClass('d-none');
                if ($('#dt_end_time').val() === default_dt_end_time) return;
                if ($('#dt_end').val() > $('#dt_start').val()) return;
                let explodedStart = $('#dt_start_time').val().split(':');
                var date1 = new Date(2000, 0, 1, explodedStart[0], explodedStart[1]);
                let explodedEnd = $('#dt_end_time').val().split(':');
                var date2 = new Date(2000, 0, 1, explodedEnd[0], explodedEnd[1]); // 5:00 PM


                if (date2 < date1) {
                    date2.setDate(date2.getDate() + 1);
                }

                var diff = date2 - date1;
                if ((diff / 1000 / 60) < 30) {
                    $('.minTimeDiffWarning').removeClass('d-none');
                }
            }


            /*FORM INPUT SHOW HIDE*/
            initForm();

            function initForm() {
                if ($('#is_multicolor').prop('checked')) {
                    $('.box__color').removeClass('d-none');
                }
            }

            $('#is_limitable').on('change', setLimitVisibility);

            function setLimitVisibility() {
                if (this.checked) {
                    $('.box__limit').removeClass('d-none');
                } else {
                    $('.box__limit').addClass('d-none');
                }
            }

            $('#is_multicolor').on('change', setColorVisiblity);

            function setColorVisiblity() {
                if (this.checked) {
                    $('.box__color').removeClass('d-none');
                    if ($('#is_limitable').prop('checked')) {
                    }
                    $('#auto_ticket_1').prop('checked', true);
                } else {
                    $('.box__color').addClass('d-none');
                }
            }

            /*ADD PRIZE*/
            $('#add_prize').on('click', function (e) {

                const idx = $('.prize_item').length;
                console.log(idx);
                e.preventDefault();
                let img = null;
                let errorMsg = $("<ul></ul>");
                let isError = false;

                const file = $('#prize_image_add')[0].files[0];
                const title = $('#prize_title_add').val();
                const description = $('#prize_description_add').val();


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
                    if (file != null) {
                        const reader = new FileReader();

                        reader.readAsDataURL(file);
                        reader.onload = function (e, idx) {
                            addImage(e, idx, title, description, img);
                        };
                    } else {
                        addImage(null, idx, title, description, img);
                    }


                }

            });


            function addImage(e, idx, title, description, img) {
                let target = '{{asset('assets/mockimage.svg')}}';

                if (e) {
                    target = e.target.result;
                }

                img = $('<img />').attr({
                    'id': 'prize',
                    'src': target,
                    'alt': title,
                    'title': title,
                    'width': 100
                });


                $('#prize_error').hide();
                const container = $('<div class="prize_item card-body row col-md-12"></div>');
                const titleGrid = $('<div class="col-md-3"><p>' + title + '</p><div class="d-none"><input name="prize_first_title_' + $('.prize_item').length + '" value="' + title + '"></div></div>');

                titleGrid.appendTo(container);


                const descGrid = $('<div class="col-md-3"><p>' + description + '</p><div class="d-none"><input name="prize_first_description_' + $('.prize_item').length + '" value="' + description + '"></div></div>');
                descGrid.appendTo(container);

                const imageGrid = $('<div class="col-md-6"></div>');
                img.appendTo(imageGrid);
                if (e) {
                    const hideContainer = $('<div class="d-none"></div>');
                    const fileInputCopy = $('#prize_image_add').clone();
                    fileInputCopy.attr('name', 'prize_image_' + $('.prize_item').length);
                    fileInputCopy.removeAttr('id');
                    fileInputCopy.appendTo(hideContainer);
                    hideContainer.appendTo(imageGrid);


                }


                imageGrid.appendTo(container);


                $('#prizes').append(container)
                $('#prizes').append($('<hr>'));
                $('#prize_image_add').next('label').html('{{__('Choose ')}}');

                $('#prize_title_add').val("");
                $('#prize_description_add').val("");
            }

            bsCustomFileInput.init();
        });
    </script>

@endpush

