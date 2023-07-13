<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Role;
use App\Models\Permission;

class RoleController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api');
        $this->middleware("check.permissions:Admin,pages.role", ['only'=>['get', 'getAll']]);
        $this->middleware("check.permissions:Admin,pages.role.modify", ['only'=>['create', 'update']]);
        $this->middleware("check.permissions:Admin,pages.role.delete", ['only'=>['delete']]);
    }

    public function create(CreateRoleRequest $request)
    {
        // Verificar si el rol ya existe
        $roleExists = Role::where('name', $request->name)->exists();
        $permissions = $request->permissions;
        if ($roleExists) {
            $role = Role::where('name', $request->name)->first();
            $existing_permissions = $role->permissions()->whereIn('name', $permissions)->get();

            // Agregar solo los permisos que no existen
            $new_permissions = array_diff($permissions, $existing_permissions->pluck('name')->toArray());

            $permissionsToSave = [];
            foreach($new_permissions as $permissionName){
                $permissionsToSave[] = [
                    'CreatorUserId' => Auth::id(),
                    'name' => $permissionName,
                    'roleId' => $role->id
                ];
            }
            if (!empty($permissionsToSave)) {
                Permission::insert($permissionsToSave);

                $permissions = $role->permissions;

                return response()->json([
                    'status' => 'success',
                    'data' => $role
                ], 201);
            }

            return response()->json([
                'status' => 'error',
                'msg' => 'The role and all permissions already exist. check carefully'
            ], 400);
        }

        $role = new Role([
            'CreatorUserId' => Auth::id(),
            'name' => $request->name,
            'isStatic' => false,
        ]);

        $role->save();

        foreach($permissions as $permission){
            $permission_save = new Permission([
                'name' => $permission,
                'roleId' => $role->id
            ]);
            $permission_save->save();
        }

        $permissions = $role->permissions;

        return response()->json([
            'status' => 'success',
            'data' => $role
        ],201);
    }

    public function delete($id)
    {
        $role = Role::where('id', $id)->where('IsDeleted', false)->first();

        if (empty($role)) {
            return response()->json([
                'status' => 'error',
                'msg' => 'The role does not exist'
            ], 404);
        }

        $role->IsDeleted = true;
        $role->DeleterUserId = Auth::id();
        $role->DeletionTime = now()->format('Y-m-d H:i:s');
        $role->save();

        return response()->json([
            'status' => 'success',
            'data' => $role
        ]);
    }

    public function get($id)
    {
        $roleExist = DB::table('roles')->where('id', $id)->get();

        if (empty($roleExist)) {
            return response()->json([
                'status' => 'error',
                'msg' => 'The role does not exist'
            ], 404);
        }

        $role = Role::where('IsDeleted', false)
            ->findOrFail($id);

        $permissions = $role->permissions;

        return response()->json([
            'status' => 'success',
            'data' => $role
        ]);
    }

    public function getAll(Request $request)
    {
        $roles = Role::where('IsDeleted', false)->get();

        $data = [];

        foreach ($roles as $role) {
            $permissions = $role->permissions;
            $data[] = [
                'id' => $role->id,
                'name' => $role->name,
                'permissions' => $permissions
            ];
        }

        $count = count($roles);

        return response()->json([
            'status' => 'success',
            'maxCount' => $count,
            'data' => $data
        ]);
    }

    public function update(UpdateRoleRequest $request, $id)
    {
        // Verificar si el rol ya existe
        $role = Role::where('id', $id)->where('IsDeleted', false)->first();

        if (empty($role)) {
            return response()->json([
                'status' => 'error',
                'msg' => 'The role does not exist'
            ], 404);
        }

        $permissions = $request->permissions;

        // Agregar los nuevos permisos
        $new_permissions = array_diff($permissions, $role->permissions->pluck('name')->toArray());

        $permissionsToSave = [];
        foreach($new_permissions as $permissionName){
            $permissionsToSave[] = [
                'name' => $permissionName,
                'roleId' => $role->id
            ];
        }
        if (!empty($permissionsToSave)) {
            Permission::insert($permissionsToSave);
        }

        $role = Role::findOrFail($id);
        $permissions = $role->permissions;

        return response()->json([
            'status' => 'success',
            'data' => $role
        ], 201);
    }
}
