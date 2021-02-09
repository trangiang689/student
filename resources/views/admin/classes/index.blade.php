@extends('adminlte::page')
@section('title', 'faculties/add faculty')

@section('content_header')
    <h1 class="title-header">Faculty <span class="small font-weight-normal">List</span></h1>
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
    <div class="form-group d-inline p-2 m-0">
        <p class="float-left">Show </p>
        <?php
        $options = array( '1' => '1', '2' => '2', '3' => '3', '5' => '5');
        ?>
        {{Form::select('checkBoxShow', $options, $limit, ['class' =>'float-left mx-2', 'id'=> 'checkBoxShow', 'style' => 'min-width: 75px;' ])}}
        <p class="float-left mr-5">entries</p>
    </div>

    <div class="form-group d-inline p-2 m-0">
        <p class="float-left">Order by</p>

        <select class="float-left mx-2 mr-5" id="" style="min-width: 250px;" name="">
            <option>Faculty name</option>
        </select>
    </div>

    <div class="form-group d-inline p-2 m-0">
        <p class="float-left">Up/down </p>

        <select class="float-left mx-2" id="" style="min-width: 50px;" name="">
            <option>Up</option>
            <option>Down</option>
        </select>
    </div>

    <table class="table table-bordered text-center table-hover" >
        <thead class="">
        <tr>
            <th>STT</th>
            <th >Class name</th>
            <th class="w-50">Faculty name</th>
            <th>Created at</th>
            <th>Updated at</th>
            <th>View</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
        </thead>
        <tbody id="myTable">
        @foreach($classes as $class)
            <tr>
                <td>{{$count++}}</td>
                <td>{{$class->name}}</td>
                <td>{{$class->faculty->name}}</td>
                <td>{{$class->created_at}}</td>
                <td>{{$class->updated_at}}</td>
                <td><a class="text-reset" href="{{route('admin.classes.show', ['class' => $class->id])}}"><i class="fas fa-eye"></i></a></td>
                <td><a class="text-reset" href="{{route('admin.classes.edit', ['class' => $class->id])}}"><i class="fas fa-edit"></i></a></td>
                <td>
                    <a class="btn-destroy" data-toggle="modal" data-target="#modalComfirmDelete"
                       data-url="{{route('admin.classes.destroy',['class' =>$class->id])}}" type="button">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                </td>
                {{--                href="{{route('admin.facultiesDestroy',['faculty' => $value->id])}}"--}}
            </tr>
        @endforeach
        </tbody>
    </table>

    {{--    modal--}}
    <!-- The Modal -->
    <div class="modal fade" id="modalComfirmDelete">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <p class="display-4  modal-title">Delete Faculty</p>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body justify-content-center">
                    <p class="text-center">Are you sure you want to delete this class?</p>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer justify-content-center">
                    {{Form::open(['method' => 'DELETE', 'url' => ' ', 'id' => 'form-destroy' ])}}
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        {{ Form::submit('Delete', ['class' => 'btn btn-success'])}}
                    {{Form::close()}}
                </div>

            </div>
        </div>
    </div>
    {{--    end modal--}}
    <ul class="pagination justify-content-center ">
        <li class="page-item"><a class="page-link" href="?limit={{$limit}}&page=1"><i class="fas fa-angle-double-left"></i></a></li>
        @if($classes->currentPage() > 1)
            <li class="page-item"><a class="page-link" href="{{$classes->appends(['limit' => $limit])->previousPageUrl()}}">{{$classes->currentPage() - 1}}</a></li>
        @endif
        <li class="page-item"><a class="page-link">{{$classes->currentPage()}}</a></li>
        @if($classes->hasMorePages())
            <li class="page-item"><a class="page-link" href="{{$classes->appends(['limit' => $limit])->nextPageUrl()}}">{{$classes->currentPage() + 1}}</a></li>
        @endif
        <li class="page-item"><a class="page-link" href="?limit={{$limit}}&page={{$classes->lastPage()}}"><i class="fas fa-angle-double-right"></i></a></li>
    </ul>
@stop

@section('css')
    <link rel="stylesheet" href="{{asset('/css/login.css')}}">
@stop
@section('js')
    <script>
        $(document).ready(function(){
            $(".btn-destroy").on("click", function($this) {
                $('#form-destroy').attr('action',$(this).attr('data-url'));
            });

            $("#checkBoxShow").on("change",function($this){
                var rs = $(this).val();
                window.location="?limit="+rs+"&page=1";
            });
        });
    </script>
@stop
