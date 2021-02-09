@extends('adminlte::page')
@section('title', 'faculties/add faculty')

@section('content_header')
    <h1 class="title-header">Faculty <span class="small font-weight-normal">Edit</span></h1>
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
        {{ Form::model($faculty, ['method' => 'PUT', 'route' => ['admin.faculties.update', $faculty->id]]) }}
        <div class="form-group">
            {{ Form::label('id', 'Faculty id :', ['class' => 'control-label']) }}
            {{ Form::text('id', $faculty->id, ['id' => 'id', 'class' => 'form-control', 'disabled' => 'true']) }}
        </div>

        <div class="form-group">
            {{ Form::label('crated_at', 'Created at :', ['class' => 'control-label']) }}
            {{ Form::date('crated_at', $faculty->created_at, ['id' => 'crated_at', 'class' => 'form-control', 'disabled' => 'true']) }}
        </div>

        <div class="form-group">
            {{ Form::label('updated_at', 'Updated at :', ['class' => 'control-label']) }}
            {{ Form::date('updated_at', $faculty->updated_at, ['id' => 'updated_at', 'class' => 'form-control', 'disabled' => 'true']) }}
        </div>
        <div class="form-group">
            {{ Form::label('name', 'Faculty name:', ['class' => 'control-label']) }}
            {{ Form::text('name', $faculty->name, ['class' => $errors->has('name') ? 'form-control border-danger' : 'form-control']) }}
            @if($errors->has('name'))
                <div class="mt-1 text-danger">{{ $errors->first('name') }}</div>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('description', 'Description', ['class' => 'control-label']) }}
            {{ Form::textarea('description', $faculty->description, ['id' => 'descriptin','class' => 'form-control', 'rows' => '10']) }}
        </div>
        <div class="row justify-content-center">
            {{Form::reset('Reset', ['class' => 'col-sm-2 btn btn-primary mx-2 mb-2 mb-sm-0'])}}
            {{Form::submit('Add',['class' => 'col-sm-2 btn btn-primary mx-2'])}}
        </div>
        {{ Form::close() }}
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{asset('/css/login.css')}}">
@stop

@section('js')
@stop
