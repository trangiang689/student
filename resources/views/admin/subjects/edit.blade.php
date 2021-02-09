@extends('adminlte::page')
@section('title', 'faculties/add faculty')

@section('content_header')
    <h1 class="title-header">Faculty <span class="small font-weight-normal">Edit</span></h1>
@stop

@section('content')
    <?php
    if (!empty(old('faculties'))) {
        $dataOldFaculties = json_encode(array_values(old('faculties')));
    } else {
        $dataOldFaculties = $facultiesDisabled;
    }
    $facultiesSelected = json_decode($facultiesDisabled);
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
    <div class="form-create-faculty">
        {{ Form::model($subject, ['method' => 'PUT', 'route' => ['admin.subjects.update', $subject->id], 'id'=> 'formEditSubject']) }}
        <div class="form-group">
            {{ Form::label('id', 'Faculty id :', ['class' => 'control-label']) }}
            {{ Form::text('id', $subject->id, ['id' => 'id', 'class' => 'form-control', 'disabled' => 'true']) }}
        </div>

        <div class="form-group">
            {{ Form::label('crated_at', 'Created at :', ['class' => 'control-label']) }}
            {{ Form::date('crated_at', $subject->created_at, ['id' => 'crated_at', 'class' => 'form-control', 'disabled' => 'true']) }}
        </div>

        <div class="form-group">
            {{ Form::label('updated_at', 'Updated at :', ['class' => 'control-label']) }}
            {{ Form::date('updated_at', $subject->updated_at, ['id' => 'updated_at', 'class' => 'form-control', 'disabled' => 'true']) }}
        </div>
        <div class="form-group">
            {{ Form::label('name', 'Faculty name:', ['class' => 'control-label']) }}
            {{ Form::text('name', $subject->name, ['class' => $errors->has('name') ? 'form-control border-danger' : 'form-control']) }}
            @if($errors->has('name'))
                <div class="mt-1 text-danger">{{ $errors->first('name') }}</div>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('description', 'Description', ['class' => 'control-label']) }}
            {{ Form::textarea('description', $subject->description, ['id' => 'descriptin','class' => 'form-control', 'rows' => '10']) }}
        </div>
        {{ Form::label('facultiesInfor', 'Use for faculties :', ['class' => 'control-label']) }}
        <div class="group-input-faculty container-fluid row">
            {{ Form::text('facultiesInfor', '', ['class' => 'd-none', 'data-disabled' => '[]', 'data-oldFaculties' => $dataOldFaculties]) }}
            <div class="col-md-6">
                @if(!empty(old('faculties')))
                    @foreach(old('faculties') as $key => $faculty)
                        <div class="input-group mb-3 row">
                            {{ Form::select('faculties['.$key.']', $faculties, $faculty,
                                  ['placeholder' => 'Pick a faculty',
                                  'id' => 'faculties-'.$key,
                                  'class' => $errors->has('faculties.'.$key) ? 'input-faculty text-capitalize custom-select border-danger' : 'input-faculty text-capitalize custom-select',
                                   'onchange' => 'changeChoseFaculty(this);' ,
                                   'onclick' => 'setOldFaculty(this);']) }}
                            <div class="input-group-append">
                                <button class="btn btn-outline-danger " onclick="deleteInputFaculty(this);" type="button">
                                    &times;
                                </button>
                            </div>
                            @if($errors->has('faculties.'.$key))
                                <div class="mt-1 text-danger col-12">{{$errors->first('faculties.'.$key)}}</div>
                            @endif
                        </div>
                    @endforeach
                @elseif(!empty($facultiesSelected))
                    @foreach($facultiesSelected as $key => $facultySelected)
                        <div class="input-group mb-3 row">
                            {{ Form::select('faculties['.$key.']', $faculties, $facultySelected,
                                  ['placeholder' => 'Pick a faculty',
                                  'id' => 'faculties-'.$key,
                                  'class' => 'input-faculty text-capitalize custom-select',
                                   'onchange' => 'changeChoseFaculty(this);' ,
                                   'onclick' => 'setOldFaculty(this);']) }}
                            <div class="input-group-append">
                                <button class="btn btn-outline-danger " onclick="deleteInputFaculty(this);" type="button">
                                    &times;
                                </button>
                            </div>
                        </div>

                    @endforeach
                @else
                    <div class="input-group mb-3 row">
                        {{ Form::select('faculties[0]', $faculties, null,
                              ['placeholder' => 'Pick a faculty',
                              'class' => 'input-faculty text-capitalize custom-select',
                               'onchange' => 'changeChoseFaculty(this);' ,
                               'onclick' => 'setOldFaculty(this);']) }}
                        <div class="input-group-append">
                            <button class="btn btn-outline-danger " onclick="deleteInputFaculty(this);" type="button">
                                &times;
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="text-left ml-2 mb-3">
            <button type="button" class="btn btn-outline-info add-input-faculty">More Faculty</button>
        </div>
        <div id="dataInputAdd" class="d-none">
            <div class="input-group mb-3 row">
                {{Form::select("", $faculties, null,
                ['placeholder' => 'Pick a faculty',
                'class' => 'input-faculty text-capitalize custom-select',
                'onchange' => 'changeChoseFaculty(this);',
                'onclick' => 'setOldFaculty(this);' ])}}
                <div class="input-group-append">
                    <button class="btn btn-outline-danger" onclick="deleteInputFaculty(this);" type="button">&times;</button>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            {{Form::reset('Reset', ['class' => 'col-sm-2 btn btn-primary mx-2 mb-2 mb-sm-0 btn-resetForm'])}}
            {{Form::submit('Edit',['class' => 'col-sm-2 btn btn-primary mx-2'])}}
        </div>
        {{ Form::close() }}
    </div>
@stop

@section('css')
@stop

@section('js')
    <script src="{{asset('/js/admin/subjects.js')}}"></script>
@stop
