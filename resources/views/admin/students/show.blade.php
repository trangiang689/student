@extends('adminlte::page')
@section('title', 'faculties/add faculty')

@section('content_header')
    <h1 class="title-header">Edit Student</h1>
@stop
@section('content')
    <?php
    //    if (!empty(old('subjects'))) {
    //        $dataOldSubjects = json_encode(array_values(old('subjects')));
    //    } else {
    //        $dataOldSubjects = json_encode(array_keys($subjectsDisabled));
    //    }
    //    $subjectsSelected = $subjectsDisabled;
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
            <div class="form-group">
                {{ Form::label('full_name', 'Full name', ['class' => 'control-label']) }}
                {{ Form::text('full_name', $student->full_name, ['class' =>'form-control' , 'disabled' => 'true']) }}
            </div>

            <div class="form-group">
                {{ Form::label('email', 'Email:', ['class' => 'control-label']) }}
                {{ Form::text('email', $student->user->email, ['class' =>  'form-control', 'disabled' => 'true']) }}
            </div>

            <div class="form-group">
                {{ Form::label('phone_number', 'Phone number:', ['class' => 'control-label']) }}
                {{ Form::text('phone_number', $student->phone_number, ['class' =>  'form-control', 'disabled' => 'true']) }}
            </div>

            <div class="form-group">
                {{ Form::label('birth_date', 'Birth day:', ['class' => 'control-label']) }}
                {{ Form::date('birth_date', $student->birth_date, ['class' =>  'form-control', 'disabled' => 'true']) }}
            </div>

            <div class="form-group">
                {{ Form::label('home_town', 'Address:', ['class' => 'control-label']) }}
                {{ Form::text('home_town', $student->home_town, ['class' =>  'form-control', 'disabled' => 'true']) }}
            </div>

            <div class="form-group">
                {{ Form::label('faculty_id', 'Faculty name:', ['class' => 'control-label']) }}
                {{ Form::text('faculty_id', $student->class->faculty->name, ['class' =>  'form-control text-capitalize', 'disabled' => 'true']) }}
            </div>

            <div class="form-group">
                {{ Form::label('class_id', 'Class name:', ['class' => 'control-label']) }}
                {{ Form::text('class_id', $student->class->name, ['class' =>  'form-control', 'disabled' => 'true']) }}
            </div>

            {{ Form::label('avatar', 'Avatar:', ['class' => 'control-label w-100']) }}

            <div class="mt-3 w-100">
                <div style="width: 250px; height: 250px; background-color: #eee" class="shadow text-center">
                    <img id="imgAvatar"
                         src="/{{\Config::get('constants.LOCATION_AVATARS')}}/{{$student->avatar}}"
                         alt="avatar"
                         class="bg-light img-fluid" style="max-height: 250px;">
                </div>
            </div>

            {{--            Scores--}}

            {{ Form::label('scoresInfo', 'Scores :', ['class' => 'control-label mt-5']) }}
            <div class="group-input-subject container-fluid row mb-5">
                <div class="col-md-7">
                    @foreach($scores as $score)
                        <div class="input-group mb-3">
                            <div class="input-group-prepend w-75">
                                <span
                                    class="input-group-text w-100 mr-3 border-info bg-light text-capitalize">{{$score->name}}</span>
                            </div>
                            {{ Form::text('', $score->pivot->score, ['class' =>  'form-control border-info bg-light', 'disabled' => 'true']) }}
                        </div>
                    @endforeach
                </div>
            </div>

            {{--            End Scores--}}
            <div class="row justify-content-center pb-5">
                <a href="{{route('admin.students.edit', ['student' => $student->id])}}"
                   class="text-white col-sm-2 btn btn-primary mx-2 mb-2 mb-sm-0">Edit</a>
                <a class="text-white col-sm-2 btn btn-primary mx-2 btn-destroy-faculty" data-toggle="modal"
                   data-target="#modalComfirmDeleteClass" type="button">Delete</a>
            </div>
        </div>

    {{--    modal--}}
    <!-- The Modal -->
        <div class="modal fade" id="modalComfirmDeleteClass">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <p class="display-4  modal-title">Delete Faculty</p>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body justify-content-center">
                        <p class="text-center">Are you sure you want to delete this subject?</p>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer justify-content-center">
                        {{Form::open(['method' => 'DELETE', 'route' => ['admin.students.destroy', $student->id] ])}}
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        {{ Form::submit('Delete', ['class' => 'btn btn-success'])}}
                        {{Form::close()}}
                    </div>

                </div>
            </div>
        </div>

        {{--    end modal--}}
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
