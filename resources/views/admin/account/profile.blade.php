@extends('adminlte::page')
@section('title', 'admin/profile')

@section('content_header')
    <h1 class="title-header">Profile</h1>
@stop

@section('content')
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
            {{ Form::model($user, ['method' => 'PUT','route' => ['admin.home.update'], 'enctype' => 'multipart/form-data' ]) }}
            <div class="form-group">
                {{ Form::label('full_name', 'Full name', ['class' => 'control-label']) }}
                {{ Form::text('full_name', $user->full_name, ['class' => $errors->has('full_name') ? 'form-control border-danger' : 'form-control']) }}
                @if($errors->has('full_name'))
                    <div class="mt-1 text-danger">{{ $errors->first('full_name') }}</div>
                @endif
            </div>

            <div class="form-group">
                {{ Form::label('', 'Roles:', ['class' => 'control-label']) }}

                @role('student')
                {{ Form::text('', 'Student', ['class' => 'form-control mb-2', 'disabled' => true]) }}
                @endrole

                @role('admin')
                {{ Form::text('', 'Admin', ['class' => 'form-control  mb-2', 'disabled' => true]) }}
                @endrole

                @role('supper-admin')
                {{ Form::text('', 'Supper Admin', ['class' => 'form-control  mb-2', 'disabled' => true]) }}
                @endrole
            </div>

            <div class="form-group">
                {{ Form::label('email', 'Email:', ['class' => 'control-label']) }}
                {{ Form::text('email', $user->user->email, ['class' => $errors->has('email') ? 'form-control border-danger' : 'form-control']) }}
                @if($errors->has('email'))
                    <div class="mt-1 text-danger">{{ $errors->first('email') }}</div>
                @endif
            </div>

            <div class="form-group">
                {{ Form::label('phone_number', 'Phone number:', ['class' => 'control-label']) }}
                {{ Form::text('phone_number', $user->phone_number, ['class' => $errors->has('phone_number') ? 'form-control border-danger' : 'form-control']) }}
                @if($errors->has('phone_number'))
                    <div class="mt-1 text-danger">{{ $errors->first('phone_number') }}</div>
                @endif
            </div>

            <div class="form-group">
                {{ Form::label('birth_date', 'Birth day:', ['class' => 'control-label']) }}
                {{ Form::date('birth_date', $user->birth_date, ['class' => $errors->has('birth_date') ? 'form-control border-danger' : 'form-control']) }}
                @if($errors->has('birth_date'))
                    <div class="mt-1 text-danger">{{ $errors->first('birth_date') }}</div>
                @endif
            </div>

            <div class="form-group">
                {{ Form::label('home_town', 'Home town:', ['class' => 'control-label']) }}
                {{ Form::text('home_town', $user->home_town, ['class' => $errors->has('home_town') ? 'form-control border-danger' : 'form-control']) }}
                @if($errors->has('home_town'))
                    <div class="mt-1 text-danger">{{ $errors->first('home_town') }}</div>
                @endif
            </div>

            @role('admin')
            <div class="form-group">
                {{ Form::label('address', 'Address:', ['class' => 'control-label']) }}
                {{ Form::text('address', $user->address, ['class' => $errors->has('address') ? 'form-control border-danger' : 'form-control']) }}
                @if($errors->has('address'))
                    <div class="mt-1 text-danger">{{ $errors->first('address') }}</div>
                @endif
            </div>
            @endrole

            @role('student')
                <div class="form-group">
                    {{ Form::label('', 'Class:', ['class' => 'control-label']) }}
                    {{ Form::text('', $user->class? $user->class->name : $user->user->student->class->name, ['class' => 'form-control', 'disabled' => true]) }}
                </div>
            @endrole

                {{ Form::label('', 'Avatar:', ['class' => 'control-label w-100']) }}
                <div class="custom-file mb-3 w-50" >
                    @if($errors->has('avatar'))
                        <input type="file" class="custom-file-input " id="avatar" name="avatar"
                               onchange="readURL(this);">
                        <label class="custom-file-label border-danger shadow" for="avatar">Choose file</label>
                        <div class="mt-1 text-danger">{{ $errors->first('avatar') }}</div>
                    @else
                        <input type="file" class="custom-file-input" id="avatar" name="avatar"
                               onchange="readURL(this);">
                        <label class="custom-file-label" for="avatar">Choose file</label>
                    @endif
                </div>

                <div class="mt-3 w-100">
                    <div style="width: 250px; height: 250px; background-color: #eee " class="text-center">
                        <img id="imgAvatar"
                             src="/{{\Config::get('constants.LOCATION_AVATARS')}}/{{empty($user->avatar)? \Config::get('constants.AVATAR_DEFAULT_NAME') : $user->avatar}}"
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
