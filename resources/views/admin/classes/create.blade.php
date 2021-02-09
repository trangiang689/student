@extends('adminlte::page')
@section('title', 'faculties/add faculty')

@section('content_header')
    <h1 class="title-header">Add Faculty</h1>
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
    <div class="form-create-faculty">
            {{ Form::model($class, ['route' => ['admin.classes.store']]) }}
            <div class="form-group">
                {{ Form::label('name', 'Class name', ['class' => 'control-label']) }}
                {{ Form::text('name', $class->name, ['class' => $errors->has('name') ? 'form-control border-danger' : 'form-control']) }}
                @if($errors->has('name'))
                    <div class="mt-1 text-danger">{{ $errors->first('name') }}</div>
                @endif
            </div>
            <div class="form-group w-50">
                {{ Form::label('faculty_id', 'Select faculty:', ['class' => 'control-label']) }}
                {{Form::select('faculty_id', $faculties, $class->faculty_id, ['placeholder' => 'Pick a faculty', 'class' => $errors->has('faculty_id') ? 'text-capitalize custom-select border-danger' : 'text-capitalize custom-select' ])}}
                @if($errors->has('faculty_id'))
                    <div class="mt-1 text-danger">{{ $errors->first('faculty_id') }}</div>
                @endif
            </div>
            <div class="row justify-content-center">
                {{Form::reset('Reset', ['class' => 'col-sm-2 btn btn-primary mx-2 mb-2 mb-sm-0'])}}
                {{Form::submit('Save',['class' => 'col-sm-2 btn btn-primary mx-2'])}}
            </div>
        {{ Form::close() }}
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{asset('/css/login.css')}}">
@stop

@section('js')
@stop
