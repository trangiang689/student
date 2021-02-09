@extends('adminlte::page')
@section('title', 'faculties/list')

@section('content_header')
    <h1 class="title-header">Decentralization <span class="small font-weight-normal">Roles</span></h1>
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
    <button class="btn btn-outline-primary mb-2" data-toggle="modal" data-target="#modalComfirmDelete">Add role
    </button>
    <table class="table table-bordered text-center table-hover">
        <thead class="">
        <tr>
            <th>STT</th>
            <th class="w-50">Role name</th>
            <th>Created at</th>
            <th>Updated at</th>
            <th>View</th>
        </tr>
        </thead>
        <tbody id="myTable">
        @foreach($roles as $role)
            <tr>
                <td>{{$count++}}</td>
                <td class="text-capitalize">{{$role->name}}</td>
                <td>{{$role->created_at}}</td>
                <td>{{$role->updated_at}}</td>
                <td><a class="text-reset" href="{{route('admin.roles.edit', ['role' => $role->id])}}"><i
                            class="fas fa-eye"></i></a></td>
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
                    <p class="modal-title" style="font-size: 2rem">Create a new role</p>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body justify-content-center">
                    <div class="form-group">
                        {{ Form::label('name', 'Role name:', ['class' => 'control-label']) }}
                        {{ Form::text('name', '', ['id' => 'name','class' => 'form-control']) }}
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    {{ Form::submit('Create', ['class' => 'btn btn-success btn-add-permisstion' ,'type' => 'button', 'data-dismiss'=>""])}}
                </div>
            </div>
        </div>
    </div>
    {{--    end modal--}}
@stop

@section('css')
@stop
@section('js')
    <script>
        $('.btn-add-permisstion').on('click', function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var name = $('#name').val();
            $.ajax({
                url: "/api/roles",
                type: "POST",
                data: {
                    _method: 'POST',
                    name: name
                },
                success: function (data) {
                    var result = JSON.parse(data);
                    if (Boolean(result.status)) {
                        $('tbody').append('<tr>\n' +
                            '                <td>'+result.data.count+'</td>\n' +
                            '                <td class="text-capitalize">'+result.data.name+'</td>\n' +
                            '                <td>'+result.date+'</td>\n' +
                            '                <td>'+result.date+'</td>\n' +
                            '                <td><a class="text-reset" href="/admin/permissions/'+result.data.id+'/edit"><i\n' +
                            '                            class="fas fa-eye"></i></a></td>\n' +
                            '            </tr>');

                        $('.modal-body').append('\n' +
                            '\n' +
                            '                    <div class="alert alert-success alert-dismissible fade show alert-create-permission">\n' +
                            '                        <button type="button" class="close" data-dismiss="alert">&times;</button>\n' +
                            '                        <strong>Success!</strong> Create role success\n' +
                            '                    </div>');
                        setTimeout(hideAlertCreatePermission, 2000);
                    } else {
                        $('.modal-body').append('\n' +
                            '\n' +
                            '                    <div class="alert alert-danger alert-dismissible fade show alert-create-permission">\n' +
                            '                        <button type="button" class="close" data-dismiss="alert">&times;</button>\n' +
                            '                        <strong>Warning!</strong>' + result.data.name[0] + '\n' +
                            '                    </div>');

                        setTimeout(hideAlertCreatePermission, 2000);
                    }
                }

            });
        });

        function hideAlertCreatePermission() {
            $('.alert-create-permission').remove();
        }
    </script>
@stop
