<?php

namespace App\Http\Controllers\Api\Decentralization;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Permissions\PermissionRepositoryInterface;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    protected $permissionRepository;

    public function __construct(PermissionRepositoryInterface $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = [];
        $permissions = $this->permissionRepository->getAll();
        if ($permissions) {
            $result['status'] = 1;
            $result['msg'] = 'OK';
            $result['data'] = $permissions;
        } else {
            $result['status'] = 0;
            $result['msg'] = 'No data';
            $result['data'] = [];
        }
        echo json_encode($result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Permission::create(['name' => 'permission edit student']);
        print_r(Permission::all());
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = [];

        $validate = \Validator::make(
            $request->all(),
            [
                'name' => [
                    'required', 'max:255', 'min:3', 'alpha_dash',
                    'unique' => 'unique:permissions,name',
                ],
            ]

        );

        if ($validate->fails()) {
            $result['status'] = 0;
            $result['msg'] = 'The data entered is not correct';
            $result['data'] = $validate->errors();
        } else {
            $permission = Permission::create(['name' => $request->get('name')]);
            if ($permission) {
                $result['status'] = 1;
                $result['msg'] = 'OK';
                $result['data'] = Permission::findByName($request->name);
                $result['data']['count'] = Permission::all()->count();
                $result['date'] = date('Y-m-d H:i:s');
            }
        }
        return json_encode($result);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
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
                Rule::in(['user', 'role']),
            ],
            'id_modal' => [
                'required',
            ],
        ];
        if ($request->has('model_name') && in_array($request->get('model_name'), ['user', 'role'])) {
            if ($request->get('model_name') == 'user') {
                $rules['id_modal']['exists'] = 'exists:users,id';
            } else {
                $rules['id_modal']['exists'] = 'exists:roles,id';
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
            $permission = Permission::find($id);
            if ($permission) {
                if ($request->get('model_name') == 'user') {
                    $object = User::find($request->get('id_modal'));
                } else {
                    $object = Role::findById($request->get('id_modal'));
                }

                if ($request->get('act') == 'delete') {
                    /************************************************remove permission*********************************************/
                    if ($object->permissions()->find($permission->id)) {
                        $deleted = $object->revokePermissionTo($permission->name);
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
                        $result['msg'] = 'The object does not have this permission';
                        $result['data'] = [];
                    }
                } else {
                    /************************************************add permission*********************************************/
                    if (!$object->permissions()->find($permission->id)) {
                        $added = $object->givePermissionTo($permission->name);
                        if ($added) {
                            $result['status'] = 1;
                            $result['msg'] = 'OK';
                            $result['data'] = $object;
                        } else {
                            $result['status'] = 0;
                            $result['msg'] = 'Add permission for object failed';
                            $result['data'] = [];
                        }
                    } else {
                        $result['status'] = 0;
                        $result['msg'] = 'The object already has this permission';
                        $result['data'] = [];
                    }
                }
            } else {
                $result['status'] = 0;
                $result['msg'] = 'Không tìm thấy permission';
                $result['data'] = [];
            }
        }
        return json_encode($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
