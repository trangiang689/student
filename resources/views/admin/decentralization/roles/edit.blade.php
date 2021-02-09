@extends('adminlte::page')
@section('title', 'faculties/list')

@section('content_header')
    <h1 class="title-header">Permission <span class="small font-weight-normal">Edit</span></h1>
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
    <div class="row" style="max-width: 500px;">
        <div class="form-group col-12">
            {{ Form::label('name', 'Permission name:', ['class' => 'control-label']) }}
            {{ Form::text('name', $role->name, ['class' => 'form-control', 'disabled' => true]) }}
        </div>
    </div>

    <div class="row ">
        <div class="uersHasPermission col-xl-6 p-0 shadow"
             style="min-height: 600px;border-radius: 20px; border: 1px solid beige;   max-height: 600px;  overflow-y: auto">
            <h1 class="text-capitalize font-weight-light text-center">User has role</h1>
            <table class="table table-bordered  text-center table-hover table-users">
                <thead class="w-100">
                <tr>
                    <th>STT</th>
                    <th class="w-50">User name</th>
                    <th>Email</th>
                    <th>Delete</th>
                </tr>
                </thead>
                <tbody id="myTable">
                @if(!empty($users))
                    @foreach($users as $key => $user)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td class="text-capitalize">{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td><a class="text-reset btnUpdatePermission" title="Remove permissions from the user"
                                   data-toggle="modal" data-target="#modalUpdatePermission"
                                   data-id="{{$user->id}}" data-modelName="user" data-act="delete"
                                   href="#"><i class="fas fa-trash-alt"></i></a></td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
            <label for="user_id" class="control-label">Add role for users:</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend w-75">
                    {{Form::select('user_id', $usersDoesntHaveRole, '',
                        ['placeholder' => 'Pick a user', 'class' =>'select-choose-id text-capitalize custom-select', 'id' => 'user_id'])}}
                </div>
                <button class="w-25 btn btn-success btnSubmitDelete" data-modelName="user"
                        data-permission-id="{{$role->id}}" data-act="add"
                        data-id="">
                    Add
                </button>
            </div>
        </div>

        <div class="rolessHasPermission col-xl-6 p-0 shadow"
             style="min-height: 600px;border-radius: 20px; border: 1px solid beige;   max-height: 600px;  overflow-y: auto">
            <h1 class="text-capitalize font-weight-light text-center">Permissions of role</h1>
            <table class="table table-bordered  text-center table-hover table-roles">
                <thead class="w-100">
                <tr>
                    <th>STT</th>
                    <th class="w-50">Permission name</th>
                    <th>Delete</th>
                </tr>
                </thead>
                <tbody id="myTable">
                @if(!empty($permissions))
                    @foreach($permissions as $key => $permission)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td class="text-capitalize">{{$permission->name}}</td>
                            <td><a class="text-reset btnUpdatePermission" title="Remove permissions from the user"
                                   data-toggle="modal" data-target="#modalUpdatePermission"
                                   data-id="{{$permission->id}}" data-modelName="permission" data-act="delete"
                                   href="#"><i class="fas fa-trash-alt"></i></a></td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
            <label for="role_id" class="control-label">Add permission for role:</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend w-75">
                    {{Form::select('role_id', $permissionsDoesntHaveRole, '',
                        ['placeholder' => 'Pick a role', 'class' =>'text-capitalize custom-select select-choose-id', 'id' => 'role_id'])}}
                </div>
                <button class="w-25 btn btn-success btnSubmitDelete" data-modelName="permission"
                        data-permission-id="{{$role->id}}" data-act="add"
                        data-id="">
                    Add
                </button>
            </div>
        </div>
    </div>




    <!-- The Modal -->
    <div class="modal fade" id="modalUpdatePermission">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <p class="display-4  modal-title">Update permission</p>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body justify-content-center">
                    <p class="text-center">Are you sure you want to remove this user's (role's) edit_student
                        permission?</p>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary btnSubmitUpdatePermission" data-modelName=""
                            data-permission-id="{{$role->id}}" data-act=""
                            data-id="">Remove
                    </button>
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
        $('.btnUpdatePermission').on('click', function () {
            $('.btnSubmitUpdatePermission').attr('data-id', $(this).attr('data-id'));
            $('.btnSubmitUpdatePermission').attr('data-modelName', $(this).attr('data-modelName'));
            $('.btnSubmitUpdatePermission').attr('data-act', $(this).attr('data-act'));
        });

        $('.select-choose-id').on('change', function () {
            $(this).parent().parent().find('.btnSubmitDelete').attr('data-id', $(this).val());
        });


        $('.btnSubmitUpdatePermission, .btnSubmitDelete').on('click', function () {
            var id_modal = $(this).attr('data-id');
            var model_name = $(this).attr('data-modelName');
            var permissionID = $(this).attr('data-permission-id');
            var act = $(this).attr('data-act');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "/api/roles/" + permissionID,
                type: "POST",
                data: {
                    _method: 'put',
                    model_name: model_name,
                    id_modal: id_modal,
                    act: act
                },
                success: function (data) {
                    var result = JSON.parse(data);
                    console.log(result);
                    if (Boolean(result.status)) {
                        if (act == 'add') {
                            if (model_name == 'user') {
                                $('#user_id  option:selected').remove();

                                $('.table-users tbody').append(' <tr>\n' +
                                    '                            <td>null</td>\n' +
                                    '                            <td class="text-capitalize">' + result.data.name + '</td>\n' +
                                    '                            <td>' + result.data.email + '</td>\n' +
                                    '                            <td><a class="text-reset btnUpdatePermission" title="Remove permissions from the user"\n' +
                                    '                                   data-toggle="modal" data-target="#modalUpdatePermission"\n' +
                                    '                                   data-id="' + result.data.id + '" data-modelName="user" data-act="delete"\n' +
                                    '                                   href="#"><i class="fas fa-trash-alt"></i></a></td>\n' +
                                    '                        </tr>');
                            } else {
                                $('#role_id  option:selected').remove();

                                $('.table-roles tbody').append('<tr>\n' +
                                    '                            <td>null</td>\n' +
                                    '                            <td class="text-capitalize">' + result.data.name + '</td>\n' +
                                    '                            <td><a class="text-reset btnUpdatePermission" title="Remove permissions from the user"\n' +
                                    '                                   data-toggle="modal" data-target="#modalUpdatePermission"\n' +
                                    '                                   data-id="' + result.data.id + '" data-modelName="permission" data-act="delete"\n' +
                                    '                                   href="#"><i class="fas fa-trash-alt"></i></a></td>\n' +
                                    '                        </tr>');
                            }
                        } else {

                            if (model_name == 'user') {
                                $('#user_id').append('<option value="' + result.data.id + '">' + result.data.email + '</option>');
                            } else {
                                $('#role_id').append('<option value="' + result.data.id + '">' + result.data.name + '</option>');
                            }
                            $(".btnUpdatePermission[data-id='" + id_modal + "'][data-modelName='" + model_name + "']").parent().parent().remove();
                            $('.modal-body').append('\n' +
                                '\n' +
                                '                    <div class="alert alert-success alert-dismissible fade show alert-create-permission">\n' +
                                '                        <button type="button" class="close" data-dismiss="alert">&times;</button>\n' +
                                '                        <strong>Success!</strong> remove this user\'s (role\'s)  permission success\n' +
                                '                    </div>');

                            setTimeout(hideAlertCreatePermission, 2000);
                        }
                    } else {
                        if (act == 'add') {
                            alert(result.msg);
                        } else {
                            $('.modal-body').append('\n' +
                                '\n' +
                                '                    <div class="alert alert-danger alert-dismissible fade show alert-create-permission">\n' +
                                '                        <button type="button" class="close" data-dismiss="alert">&times;</button>\n' +
                                '                        <strong>Warning!</strong>' + result.msg + '\n' +
                                '                    </div>');

                            setTimeout(hideAlertCreatePermission, 2000);
                        }
                    }
                }

            });
        });

        function hideAlertCreatePermission() {
            $('.alert-create-permission').remove();
        }
    </script>
@stop
