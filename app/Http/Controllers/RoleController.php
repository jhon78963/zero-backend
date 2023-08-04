<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('access.role');
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
                'CreatorUserId' => Auth::id(),
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
        $role->update(['name' => $request->name]);
        $permissions = $role->permissions;

        return response()->json([
            'status' => 'success',
            'data' => $role
        ], 201);
    }

    public function revoke(Request $request, $id)
    {
        // Verificar si el rol ya existe
        $role = Role::where('id', $id)->where('IsDeleted', false)->first();

        if (empty($role)) {
            return response()->json([
                'status' => 'error',
                'msg' => 'The role does not exist'
            ], 404);
        }

        $role_permissions = $role->permissions;

        $permissionExists = false;

        foreach ($role_permissions as $role_permission) {
            if ($role_permission->name == $request->name) {
                Permission::where('name', $request->name)->where('roleId', $id)->delete();
                $permissionExists = true;
                break;
            }
        }

        if ($permissionExists) {
            return response()->json([
                'status' => 'success',
                'msg' => 'The permission has been revoked'
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'msg' => 'The permission does not exist or has already been revoked.'
            ], 404);
        }
    }
}
