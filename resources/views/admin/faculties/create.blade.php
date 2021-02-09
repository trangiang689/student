@extends('adminlte::page')
@section('title', 'faculties/add faculty')

@section('content_header')
    <h1 class="title-header">{{trans('faculties.faculty')}} <span
            class="small font-weight-normal">{{trans('faculties.add')}}</span></h1>
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

    <div class="dropdown">
        <button type="button" class="d-block  ml-auto  btn btn-primary dropdown-toggle mr-4 p-1 text-capitalize"
                data-toggle="dropdown" style="width: 150px">
            {{trans('faculties.language')}}
        </button>
        <div class="dropdown-menu p-0">
            <a class="dropdown-item text-capitalize"
               href="{!! route('user.change-language', ['en']) !!}">{{trans('faculties.english')}}</a>
            <a class="dropdown-item text-capitalize"
               href="{!! route('user.change-language', ['vi']) !!}">{{trans('faculties.vietnamese')}}</a>
        </div>
    </div>
    {{ Form::model($faculty, ['route' => ['admin.faculties.store'] , 'class' => '', 'style'=> 'min-height: 100vh;']) }}
    <div class="form-create-faculty row">
        <div class="col-md-6">
            <h3 class="text-center font-italic text-capitalize">{{trans('faculties.vietnamese')}}</h3>
            <div class="form-group">
                {{ Form::label('vi[name]', trans('faculties.faculty_name'), ['class' => 'control-label']) }}
                {{ Form::text('vi[name]', $faculty->name, ['class' => $errors->has('vi.name') ? 'form-control border-danger' : 'form-control']) }}
                @if($errors->has('vi.name'))
                    <div class="mt-1 text-danger">{{ $errors->first('vi.name') }}</div>
                @endif
            </div>

            <div class="form-group">
                {{ Form::label('vi[description]', trans('faculties.description'), ['class' => 'control-label text-capitalize']) }}
                {{ Form::textarea('vi[description]', $faculty->description,
                    ['class' =>$errors->has('vi.description')? 'form-control border-danger' : 'form-control', 'rows' => '10']) }}
            </div>
            @if($errors->has('vi.description'))
                <div class="mt-1 text-danger">{{ $errors->first('vi.description') }}</div>
            @endif
        </div>

        <div class="col-md-6">
            <h3 class="text-center font-italic text-capitalize">{{trans('faculties.english')}}</h3>
            <div class="form-group">
                {{ Form::label('en[name]', trans('faculties.faculty_name'), ['class' => 'control-label']) }}
                {{ Form::text('en[name]', $faculty->name, ['class' => $errors->has('en.name') ? 'form-control border-danger' : 'form-control']) }}
                @if($errors->has('en.name'))
                    <div class="mt-1 text-danger">{{ $errors->first('en.name') }}</div>
                @endif
            </div>

            <div class="form-group">
                {{ Form::label('en[description]', trans('faculties.description'), ['class' => 'control-label text-capitalize']) }}
                {{ Form::textarea('en[description]', $faculty->description,
                    ['class' =>$errors->has('en.description')? 'form-control border-danger' : 'form-control', 'rows' => '10']) }}
            </div>
            @if($errors->has('en.description'))
                <div class="mt-1 text-danger">{{ $errors->first('en.description') }}</div>
            @endif
        </div>
    </div>
    <div class="row justify-content-center">
        {{Form::reset(trans('faculties.reset'), ['class' => 'col-sm-2 btn btn-primary mx-2 mb-2 mb-sm-0 text-capitalize'])}}
        {{Form::submit(trans('faculties.add'),['class' => 'col-sm-2 btn btn-primary mx-2 text-capitalize'])}}
    </div>
    {{ Form::close() }}
@stop

@section('css')
    <link rel="stylesheet" href="{{asset('/css/login.css')}}">
@stop
@section('js')
@stop
