@extends('adminlte::page')
@section('title', 'faculties/add faculty')

@section('content_header')
    <h1 class="title-header">Edit Student</h1>
@stop
@section('content')
    <?php
    if (!empty(old('subjects'))) {
        $dataOldSubjects = json_encode(array_values(old('subjects')));
    } else {
        $dataOldSubjects = json_encode(array_keys($subjectsDisabled));
    }
    $subjectsSelected = $subjectsDisabled;
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
            {{ Form::model($studentModel, ['method' => 'PUT','route' => ['admin.students.update', $student->id],'id'=> 'formEditStudent', 'enctype' => 'multipart/form-data' ] ) }}
            <div class="form-group">
                {{ Form::label('full_name', 'Full name', ['class' => 'control-label']) }}
                {{ Form::text('full_name', $student->full_name, ['class' => $errors->has('full_name') ? 'form-control border-danger' : 'form-control']) }}
                @if($errors->has('full_name'))
                    <div class="mt-1 text-danger">{{ $errors->first('full_name') }}</div>
                @endif
            </div>

            <div class="form-group">
                {{ Form::label('email', 'Email:', ['class' => 'control-label']) }}
                {{ Form::text('email', $student->user->email, ['class' => $errors->has('email') ? 'form-control border-danger' : 'form-control']) }}
                @if($errors->has('email'))
                    <div class="mt-1 text-danger">{{ $errors->first('email') }}</div>
                @endif
            </div>

            <div class="form-group">
                {{ Form::label('phone_number', 'Phone number:', ['class' => 'control-label']) }}
                {{ Form::text('phone_number', $student->phone_number, ['class' => $errors->has('phone_number') ? 'form-control border-danger' : 'form-control']) }}
                @if($errors->has('phone_number'))
                    <div class="mt-1 text-danger">{{ $errors->first('phone_number') }}</div>
                @endif
            </div>

            <div class="form-group">
                {{ Form::label('birth_date', 'Birth day:', ['class' => 'control-label']) }}
                {{ Form::date('birth_date', $student->birth_date, ['class' => $errors->has('birth_date') ? 'form-control border-danger' : 'form-control']) }}
                @if($errors->has('birth_date'))
                    <div class="mt-1 text-danger">{{ $errors->first('birth_date') }}</div>
                @endif
            </div>

            <div class="form-group">
                {{ Form::label('home_town', 'Address:', ['class' => 'control-label']) }}
                {{ Form::text('home_town', $student->home_town, ['class' => $errors->has('home_town') ? 'form-control border-danger' : 'form-control']) }}
                @if($errors->has('home_town'))
                    <div class="mt-1 text-danger">{{ $errors->first('home_town') }}</div>
                @endif
            </div>

            <div class="form-group w-50">
                {{ Form::label('faculty_id', 'Select faculty:', ['class' => 'control-label']) }}
                {{Form::select('faculty_id', $faculties, $student->class->faculty->id,
                    ['placeholder' => 'Pick a faculty', 'class' => $errors->has('faculty_id') ? 'text-capitalize custom-select border-danger' : 'text-capitalize custom-select' ])}}
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
                                    @if($class->id == $student->class_id)
                                        <option class="d-none" selected data-parentFaculty="{{$class->faculty_id}}"
                                                value="{{$class->id}}">{{$class->name}}</option>
                                    @else
                                        <option class="d-none" data-parentFaculty="{{$class->faculty_id}}"
                                                value="{{$class->id}}">{{$class->name}}</option>
                                    @endif
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
                         src="/{{\Config::get('constants.LOCATION_AVATARS')}}/{{$student->avatar}}"
                         alt="avatar"
                         class="bg-light img-fluid" style="max-height: 250px;">
                </div>
            </div>

            {{--            Scores--}}

            {{ Form::label('scoresInfo', 'Scores :', ['class' => 'control-label mt-5']) }}
            <div class="group-input-subject container-fluid row">
                {{ Form::text('scoresInfo', '', ['class' => 'd-none', 'data-disabled' => '[]', 'data-oldSubjects' => $dataOldSubjects]) }}
                <div class="col-md-7">
                    @if(!empty(old('subjects')))
                        @foreach(old('subjects') as $key => $subject)
                            <div class="input-group mt-3 mb-3">
                                <div class="input-group-prepend w-75 mr-2">
                                    {{ Form::select('subjects['.$key.']', $subjects, $subject,
                                          ['placeholder' => 'Pick a subject',
                                            'id' => 'subjects-'.$key,
                                          'class' => $errors->has('subjects.'.$key) ? 'input-subject w-100 border-danger': 'input-subject w-100',
                                           'onchange' => 'changeChoseSubject(this);' ,
                                           'onclick' => 'setOldSubject(this);']) }}
                                </div>
                                {{ Form::text('scores['.$key.']', old('scores')[$key],
                                    ['class' => $errors->has('scores.'.$key) ? 'input-score form-control mr-2 border-info border-danger': 'input-score form-control mr-2 border-info',
                                    'placeholder' => "Score"]) }}
                                <div class="input-group-append">
                                    <button class="btn btn-outline-danger rounded" onclick="deleteInputSubject(this);"
                                            type="button">
                                        ×
                                    </button>
                                </div>
                                @if($errors->has('subjects.'.$key))
                                    <div class="mt-1 text-danger col-12">{{$errors->first('subjects.'.$key)}}</div>
                                @endif
                                @if($errors->has('scores.'.$key))
                                    <div class="mt-1 text-danger col-12">{{$errors->first('scores.'.$key)}}</div>
                                @endif
                            </div>
                        @endforeach
                    @elseif(!empty($subjectsSelected))
                        @foreach($subjectsSelected as $subjectSelected => $score)
                            <div class="input-group mt-3 mb-3">
                                <div class="input-group-prepend w-75 mr-2">
                                    {{ Form::select('subjects[]', $subjects, $subjectSelected,
                                          ['placeholder' => 'Pick a subject',
                                          'class' => 'input-subject w-100',
                                           'onchange' => 'changeChoseSubject(this);' ,
                                           'onclick' => 'setOldSubject(this);']) }}
                                </div>
                                {{ Form::text('scores[]', $score, ['class' =>'input-score form-control mr-2 border-info', 'placeholder' => "Score"]) }}
                                <div class="input-group-append">
                                    <button class="btn btn-outline-danger rounded" onclick="deleteInputSubject(this);"
                                            type="button">
                                        ×
                                    </button>
                                </div>
                            </div>

                        @endforeach
                    @else
                        <div class="input-group mt-3 mb-3">
                            <div class="input-group-prepend w-75 mr-2">
                                {{ Form::select('subjects[]', $subjects, null,
                                      ['placeholder' => 'Pick a subjects',
                                      'class' => 'input-subject w-100',
                                       'onchange' => 'changeChoseSubject(this);' ,
                                       'onclick' => 'setOldSubject(this);']) }}
                            </div>
                            {{ Form::text('scores[]', null, ['class' =>'input-score form-control mr-2 border-info', 'placeholder' => "Score"]) }}
                            <div class="input-group-append">
                                <button class="btn btn-outline-danger rounded" onclick="deleteInputSubject(this);"
                                        type="button">
                                    ×
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="text-left ml-2 mb-3">
                <button type="button" class="btn btn-outline-info add-input-subject">More Score</button>
            </div>
            <div id="dataInputAdd" class="d-none">

                <div class="input-group mt-3 mb-3">
                    <div class="input-group-prepend w-75 mr-2">
                        {{ Form::select("", $subjects, null,
                              ['placeholder' => 'Pick a subjects',
                              'class' => 'input-subject w-100',
                               'onchange' => 'changeChoseSubject(this);' ,
                               'onclick' => 'setOldSubject(this);']) }}
                    </div>
                    {{ Form::text("", null, ['class' =>'input-score form-control mr-2 border-info', 'placeholder' => "Score"]) }}
                    <div class="input-group-append">
                        <button class="btn btn-outline-danger rounded" onclick="deleteInputSubject(this);"
                                type="button">
                            ×
                        </button>
                    </div>
                </div>

            </div>

            {{--            End Scores--}}
            <div class="row justify-content-center pb-5">
                {{Form::reset('Reset', ['class' => 'col-sm-2 btn btn-primary mx-2 mb-2 mb-sm-0 btn-resetForm'])}}
                {{Form::submit('Save',['class' => 'col-sm-2 btn btn-primary mx-2'])}}
            </div>
            {{ Form::close() }}
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
    <script src="{{asset('/js/admin/students.js')}}"></script>
@stop
