@extends('adminlte::page')
@section('title', 'faculties/add faculty')

@section('content_header')
    <h1 class="title-header">Mails <span class="small font-weight-normal">Index</span></h1>
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

    <table class="table table-bordered text-center table-hover" >
        <thead class="">
        <tr>
            <th>STT</th>
            <th >Email</th>
            <th class="w-50">Description</th>
            <th>Send</th>
        </tr>
        </thead>
        <tbody id="myTable">
        <tr>
            <td>1</td>
            <td>Expulsion mail</td>
            <td>Gửi mail đuổi học tới những học sinh dưới 5 điểm</td>
            <td><a class="text-reset" href="{{route('admin.sendMailsExpilsion')}}">
                    <button class="btn btn-outline-primary"><i class="far fa-paper-plane"></i></button></a></td>
        </tr>
        </tbody>
    </table>
@stop

@section('css')
@stop
@section('js')
@stop
