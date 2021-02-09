@extends('adminlte::page')
@section('title', 'faculties/add faculty')

@section('content_header')
    <h1 class="title-header">Add Student</h1>
@stop
@section('content')
    <?php
    //    dd(old());
    ?>
    @if(Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Success!</strong> {{Session::get('success')}}
        </div>
    @endif
    @if(Session::has('warning'))
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Warning!</strong> {!!Session::get('warning') !!}.
        </div>
    @endif
    <div class="container-fluid ">
        <div class="form-create-faculty" style="max-width: 1000px">
            {{ Form::model($studentModel, ['route' => ['admin.students.store'], 'enctype' => 'multipart/form-data' ]) }}
            <div class="form-group">
                {{ Form::label('full_name', 'Full name', ['class' => 'control-label']) }}
                {{ Form::text('full_name', $studentModel->name, ['class' => $errors->has('full_name') ? 'form-control border-danger' : 'form-control']) }}
                @if($errors->has('full_name'))
                    <div class="mt-1 text-danger">{{ $errors->first('full_name') }}</div>
                @endif
            </div>

            <div class="form-group">
                {{ Form::label('email', 'Email:', ['class' => 'control-label']) }}
                {{ Form::text('email', $studentModel->email, ['class' => $errors->has('email') ? 'form-control border-danger' : 'form-control']) }}
                @if($errors->has('email'))
                    <div class="mt-1 text-danger">{{ $errors->first('email') }}</div>
                @endif
            </div>

            <div class="form-group">
                {{ Form::label('phone_number', 'Phone number:', ['class' => 'control-label']) }}
                {{ Form::text('phone_number', $studentModel->phone_number, ['class' => $errors->has('phone_number') ? 'form-control border-danger' : 'form-control']) }}
                @if($errors->has('phone_number'))
                    <div class="mt-1 text-danger">{{ $errors->first('phone_number') }}</div>
                @endif
            </div>

            <div class="form-group">
                {{ Form::label('birth_date', 'Birth day:', ['class' => 'control-label']) }}
                {{ Form::date('birth_date', $studentModel->birth_day, ['class' => $errors->has('birth_date') ? 'form-control border-danger' : 'form-control']) }}
                @if($errors->has('birth_date'))
                    <div class="mt-1 text-danger">{{ $errors->first('birth_date') }}</div>
                @endif
            </div>

            <div class="form-group">
                {{ Form::label('home_town', 'Address:', ['class' => 'control-label']) }}
                {{ Form::text('home_town', $studentModel->home_town, ['class' => $errors->has('home_town') ? 'form-control border-danger' : 'form-control']) }}
                @if($errors->has('home_town'))
                    <div class="mt-1 text-danger">{{ $errors->first('home_town') }}</div>
                @endif
            </div>

            <div class="form-group w-50">
                {{ Form::label('faculty_id', 'Select faculty:', ['class' => 'control-label']) }}
                {{Form::select('faculty_id', $faculties, null, ['placeholder' => 'Pick a faculty', 'class' => $errors->has('faculty_id') ? 'text-capitalize custom-select border-danger' : 'text-capitalize custom-select' ])}}
                @if($errors->has('faculty_id'))
                    <div class="mt-1 text-danger">{{ $errors->first('faculty_id') }}</div>
                @endif
            </div>

            <div class="form-group w-50">
                {{ Form::label('class_id', 'Select class:', ['class' => 'control-label']) }}
                <select class="custom-select" id="class_id" name="class_id">
                    <option value>Pick a class</option>
                    <div class="optionsOfClasses">
                        @foreach($classes as $key => $value)
                            @foreach($value as $class)
                                @if(!empty(old('class_id')) && old('class_id')== $class->id)
                                    <option class="d-none" selected data-parentFaculty="{{$class->faculty_id}}"
                                            value="{{$class->id}}">{{$class->name}}</option>
                                @else
                                    <option class="d-none" data-parentFaculty="{{$class->faculty_id}}"
                                            value="{{$class->id}}">{{$class->name}}</option>
                                @endif
                            @endforeach
                        @endforeach
                    </div>
                </select>
                @if($errors->has('class_id'))
                    <div class="mt-1 text-danger">{{ $errors->first('class_id') }}</div>
                @endif
            </div>

            {{ Form::label('', 'Avatar:', ['class' => 'control-label w-100']) }}
            <div class="custom-file mb-3 w-50">
                @if($errors->has('avatar'))
                    <input type="file" class="custom-file-input " id="avatar" name="avatar" onchange="readURL(this);">
                    <label class="custom-file-label border-danger shadow" for="avatar">Choose file</label>
                    <div class="mt-1 text-danger">{{ $errors->first('avatar') }}</div>
                @else
                    <input type="file" class="custom-file-input" id="avatar" name="avatar" onchange="readURL(this);">
                    <label class="custom-file-label" for="avatar">Choose file</label>
                @endif
            </div>

            <div class="mt-3 w-100">
                <div style="width: 250px; height: 250px; background-color: #eee " class="text-center">
                    <img id="imgAvatar"
                         src="/{{\Config::get('constants.LOCATION_AVATARS')}}/{{\Config::get('constants.AVATAR_DEFAULT_NAME')}}"
                         alt="avatar"
                         class="bg-light img-fluid" style="max-height: 250px;">
                </div>
            </div>
            <div class="row justify-content-center pb-5">
                {{Form::reset('Reset', ['class' => 'col-sm-2 btn btn-primary mx-2 mb-2 mb-sm-0'])}}
                {{Form::submit('Save',['class' => 'col-sm-2 btn btn-primary mx-2'])}}
            </div>
            {{ Form::close() }}
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
    <script>
        $(".custom-file-input").on("change", function () {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
        $("select[name = faculty_id]").on('change', function () {
            var facultySelected = $(this).val();
            $('select[name = class_id] option').removeClass('d-none');
            $('select[name = class_id] option[data-parentFaculty]').addClass('d-none');
            $('select[name = class_id] option[data-parentFaculty =' + facultySelected + ']').removeClass('d-none');
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#imgAvatar')
                        .attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@stop
