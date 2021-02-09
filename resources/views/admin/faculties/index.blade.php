@extends('adminlte::page')
@section('title', 'faculties/list')

@section('content_header')
    <h1 class="title-header text-capitalize">{{trans('faculties.faculty')}} <span
            class="small font-weight-normal">{{trans('faculties.list')}}</span></h1>
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
        <p class="float-left">{{trans('faculties.show')}} </p>
        <?php
        $options = array('1' => '1', '2' => '2', '3' => '3', '5' => '5');
        ?>
        {{Form::select('checkBoxShow', $options, $limit, ['class' =>'float-left mx-2', 'id'=> 'checkBoxShow', 'style' => 'min-width: 75px;' ])}}
        <p class="float-left mr-5">{{trans('faculties.entries')}}</p>

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

    </div>

    <table class="table table-bordered text-center table-hover">
        <thead class="">
        <tr>
            <th>STT</th>
            <th class="w-50 ">{{trans('faculties.faculty_name')}}</th>
            <th class="">{{trans('faculties.created_at')}}</th>
            <th class="">{{trans('faculties.updated_at')}}</th>
            <th class="">{{trans('faculties.view')}}</th>
            <th class="">{{trans('faculties.edit')}}</th>
            <th class="">{{trans('faculties.delete')}}</th>
        </tr>
        </thead>
        <tbody id="myTable">
        @foreach($faculties as $faculty)
            <tr>
                <td>{{$count++}}</td>
                <td class="text-capitalize">{{$faculty->name}}</td>
                <td>{{$faculty->created_at}}</td>
                <td>{{$faculty->updated_at}}</td>
                <td><a class="text-reset"
                       href="{{route('admin.faculties.show', ['faculty' => empty($faculty->slug)? $faculty->id : $faculty->slug])}}"><i
                            class="fas fa-eye"></i></a></td>
                <td><a class="text-reset"
                       href="{{route('admin.faculties.edit', ['faculty' => empty($faculty->slug)? $faculty->id : $faculty->slug])}}"><i
                            class="fas fa-edit"></i></a></td>
                <td>
                    <a class="btn-destroy" data-toggle="modal" data-target="#modalComfirmDelete"
                       data-url="{{route('admin.faculties.destroy',['faculty' => $faculty->id])}}" type="button">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                </td>
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
                    <p class="display-4  modal-title">{{trans('faculties.header_delete_box')}}</p>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body justify-content-center">
                    <p class="text-center">{{trans('faculties.content_delete_box')}}</p>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer justify-content-center">
                    {{Form::open(['method' => 'DELETE', 'url' => ' ', 'id' => 'form-destroy' ])}}
                    <button type="button" class="btn btn-danger"
                            data-dismiss="modal">{{trans('faculties.cancel')}}</button>
                    {{ Form::submit(trans('faculties.delete'), ['class' => 'btn btn-success'])}}
                    {{Form::close()}}
                </div>

            </div>
        </div>
    </div>
    {{--    end modal--}}
    <ul class="pagination justify-content-center ">
        <li class="page-item"><a class="page-link" href="?limit={{$limit}}&page=1"><i
                    class="fas fa-angle-double-left"></i></a></li>
        @if($faculties->currentPage() > 1)
            <li class="page-item"><a class="page-link"
                                     href="{{$faculties->appends(['limit' => $limit])->previousPageUrl()}}">{{$faculties->currentPage() - 1}}</a>
            </li>
        @endif
        <li class="page-item"><a class="page-link">{{$faculties->currentPage()}}</a></li>
        @if($faculties->hasMorePages())
            <li class="page-item"><a class="page-link"
                                     href="{{$faculties->appends(['limit' => $limit])->nextPageUrl()}}">{{$faculties->currentPage() + 1}}</a>
            </li>
        @endif
        <li class="page-item"><a class="page-link" href="?limit={{$limit}}&page={{$faculties->lastPage()}}"><i
                    class="fas fa-angle-double-right"></i></a></li>
    </ul>
@stop

@section('css')
    <link rel="stylesheet" href="{{asset('/css/login.css')}}">
@stop
@section('js')
    <script>
        $(document).ready(function () {
            $(".btn-destroy").on("click", function ($this) {
                $('#form-destroy').attr('action', $(this).attr('data-url'));
            });

            $("#checkBoxShow").on("change", function ($this) {
                var rs = $(this).val();
                // alert(rs);
                window.location = "?limit=" + rs + "&page=1";
            });
        });
    </script>
@stop
