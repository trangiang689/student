<?php

namespace App\Http\Controllers\Api\decentralization;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Dotenv\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::find(53);
        $role = Role::findById(3);
        $permission = Permission::findById(1);
        $permission->assignRole($role->name);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = \Validator::make(
            $request->all(),
            [
                'name' => [
                    'required', 'max:255', 'min:3', 'alpha_dash',
                    'unique' => 'unique:roles,name',
                ],
            ]

        );

        if ($validate->fails()) {
            $result['status'] = 0;
            $result['msg'] = 'The data entered is not correct';
            $result['data'] = $validate->errors();
        } else {
            $permission = Role::create(['name' => $request->get('name')]);
            if ($permission) {
                $result['status'] = 1;
                $result['msg'] = 'OK';
                $result['data'] = Role::findByName($request->name);
                $result['data']['count'] = Role::all()->count();
                $result['date'] = date('Y-m-d H:i:s');
            }
        }
        return json_encode($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = [];
        $rules = [
            'act' => [
                'required',
                Rule::in(['add', 'delete']),
            ],
            'model_name' => [
                'required',
                Rule::in(['user', 'permission']),
            ],
            'id_modal' => [
                'required',
            ],
        ];
        if ($request->has('model_name') && in_array($request->get('model_name'), ['user', 'permission'])) {
            if ($request->get('model_name') == 'user') {
                $rules['id_modal']['exists'] = 'exists:users,id';
            } else {
                $rules['id_modal']['exists'] = 'exists:permissions,id';
            }
        }
        $validate = \Validator::make(
            $request->all(),
            $rules
        );

        if ($validate->fails()) {
            $result['status'] = 0;
            $result['msg'] = 'The data entered is not correct';
            $result['data'] = $validate->errors();
        } else {
            $role = Role::find($id);
            if ($role) {
                if ($request->get('model_name') == 'user') {
                    $object = User::find($request->get('id_modal'));
                } else {
                    $object = Permission::findById($request->get('id_modal'));
                }

                if ($request->get('act') == 'delete') {
                    /************************************************remove permission*********************************************/
                    if ($object->roles()->find($role->id)) {
                        $deleted = $object->removeRole($role->name);
                        if ($deleted) {
                            $result['status'] = 1;
                            $result['msg'] = 'OK';
                            $result['data'] = $object;
                        } else {
                            $result['status'] = 0;
                            $result['msg'] = 'Xóa không quyền không thành công';
                            $result['data'] = [];
                        }
                    } else {
                        $result['status'] = 0;
                        $result['msg'] = 'The object does not have this role';
                        $result['data'] = [];
                    }
                } else {
                    /************************************************add permission*********************************************/
                    if (!$object->roles()->find($role->id)) {
                        $added = $object->assignRole($role->name);
                        if ($added) {
                            $result['status'] = 1;
                            $result['msg'] = 'OK';
                            $result['data'] = $object;
                        } else {
                            $result['status'] = 0;
                            $result['msg'] = 'Add role for object failed';
                            $result['data'] = [];
                        }
                    } else {
                        $result['status'] = 0;
                        $result['msg'] = 'The object already has this role';
                        $result['data'] = [];
                    }
                }
            } else {
                $result['status'] = 0;
                $result['msg'] = 'Không tìm thấy role';
                $result['data'] = [];
            }
        }
        return json_encode($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
