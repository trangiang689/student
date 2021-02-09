@extends('adminlte::page')
@section('title', 'faculties/add faculty')

@section('content_header')
    <h1 class="title-header">Faculty Show</h1>
@stop

@section('content')
    <div class="form-create-faculty">
        <div class="form-group">
            {{ Form::label('id', 'Faculty id :', ['class' => 'control-label']) }}
            {{ Form::text('id', $faculty->id, ['id' => 'id', 'class' => 'form-control', 'disabled' => 'true']) }}
        </div>
        <div class="form-group">
            {{ Form::label('name', 'Faculty name :', ['class' => 'control-label']) }}
            {{ Form::text('name', $faculty->name, ['id' => 'name', 'class' => 'text-capitalize form-control', 'disabled' => 'true']) }}
        </div>

        <div class="form-group">
            {{ Form::label('crated_at', 'Create at :', ['class' => 'control-label']) }}
            {{ Form::date('crated_at', $faculty->created_at, ['id' => 'crated_at', 'class' => 'form-control', 'disabled' => 'true']) }}
        </div>

        <div class="form-group">
            {{ Form::label('updated_at', 'Updated at :', ['class' => 'control-label']) }}
            {{ Form::date('updated_at', $faculty->updated_at, ['id' => 'updated_at', 'class' => 'form-control', 'disabled' => 'true']) }}
        </div>

        <div class="form-group">
            {{ Form::label('description', 'Description', ['class' => 'control-label']) }}
            {{ Form::textarea('description', $faculty->description, ['id' => 'descriptin','class' => 'form-control', 'rows' => '10', 'disabled' => 'true']) }}
        </div>

        <div class="form-group mt-5">
            {{ Form::label('subjects', 'Subjects :', ['class' => 'control-label']) }}
            <div class="p-3">
                @foreach($subjects as $subject)
                    {{ Form::text('subjects', $subject->name, ['id' => 'name', 'style' => 'max-width :800px;', 'class' => 'mb-2 form-control text-capitalize', 'disabled' => 'true']) }}
                @endforeach
            </div>
        </div>

        <div class="row justify-content-center">
            <a href="{{route('admin.faculties.edit', ['faculty' => $faculty->id])}}"
               class="text-white col-sm-2 btn btn-primary mx-2 mb-2 mb-sm-0">Edit</a>
            <a class="text-white col-sm-2 btn btn-primary mx-2 btn-destroy-faculty" data-toggle="modal"
               data-target="#modalComfirmDeleteFaculty" type="button">Delete</a>
        </div>
    </div>

    {{--    modal--}}
    <!-- The Modal -->
    <div class="modal fade" id="modalComfirmDeleteFaculty">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <p class="display-4  modal-title">Delete Faculty</p>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body justify-content-center">
                    <p class="text-center">Are you sure you want to delete this faculty?</p>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer justify-content-center">
                    {{Form::open(['method' => 'DELETE', 'route' => ['admin.faculties.destroy', $faculty->id] ])}}
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        {{ Form::submit('Delete', ['class' => 'btn btn-success'])}}
                    {{Form::close()}}
                </div>

            </div>
        </div>
    </div>
    {{--    end modal--}}
@stop

@section('css')
    <link rel="stylesheet" href="{{asset('/css/login.css')}}">
@stop

@section('js')
@stop
