@extends('adminlte::page')
@section('title', 'faculties/add faculty')

@section('content_header')
    <h1 class="title-header">Add Faculty</h1>
@stop
@section('content')
    <?php
    if (!empty(old('faculties'))) {
        $dataOldFaculties = json_encode(old('faculties'));
    } else {
        $dataOldFaculties = json_encode([]);
    }
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
    {{ Form::model($subject, ['method' => 'POST', 'route' => ['admin.subjects.store'], 'id'=> 'formEditSubject' ,'class' => 'pb-5' ])}}
    <div class="form-group">
        {{ Form::label('name', 'Subject name', ['class' => 'control-label']) }}
        {{ Form::text('name', '', ['class' => $errors->has('name') ? 'form-control border-danger' : 'form-control']) }}
        @if($errors->has('name'))
            <div class="mt-1 text-danger">{{ $errors->first('name') }}</div>
        @endif
    </div>
    <div class="form-group">
        {{ Form::label('description', 'Description', ['class' => 'control-label']) }}
        {{ Form::textarea('description', '', ['class' => 'form-control', 'rows' => '10']) }}
    </div>
    <div class="row justify-content-center">
        {{Form::reset('Reset', ['class' => 'btn-resetForm col-sm-2 btn btn-primary mx-2 mb-2 mb-sm-0'])}}
        {{Form::submit('Add',['class' => 'col-sm-2 btn btn-primary mx-2'])}}
    </div>
    {{ Form::close() }}
@stop

@section('css')
@stop
@section('js')
@stop
