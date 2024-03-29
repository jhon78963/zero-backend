<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\AcademicPeriod;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.permissions:Admin,pages.role')->only(['index', 'getAll', 'get']);
        $this->middleware('check.permissions:Admin,pages.role.modify')->only(['create', 'update']);
        $this->middleware('check.permissions:Admin,pages.role.delete')->only(['delete']);
    }

    public function index($period_name)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();
        return view('access.role.index', compact('period'));
    }

    public function create(CreateRoleRequest $request, $period_id)
    {
        // Verificar si el rol ya existe
        $roleExists = Role::where('name', $request->name)->where('IsDeleted', 0)->where('TenantId', $period_id)->exists();
        $permissions = $request->permissions;
        if ($roleExists) {
            $role = Role::where('name', $request->name)->where('IsDeleted', 0)->where('TenantId', $period_id)->first();
            $existing_permissions = $role->permissions()->whereIn('name', $permissions)->get();

            // Agregar solo los permisos que no existen
            $new_permissions = array_diff($permissions, $existing_permissions->pluck('name')->toArray());

            $permissionsToSave = [];
            foreach ($new_permissions as $permissionName) {
                $permissionsToSave[] = [
                    'CreatorUserId' => Auth::id(),
                    'TenantId' => $period_id,
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
            'TenantId' => $period_id,
            'name' => $request->name,
            'isStatic' => false,
        ]);

        $role->save();

        foreach ($permissions as $permission) {
            $permission_save = new Permission([
                'CreatorUserId' => Auth::id(),
                'TenantId' => $period_id,
                'name' => $permission,
                'roleId' => $role->id
            ]);
            $permission_save->save();
        }

        $permissions = $role->permissions;

        return response()->json([
            'status' => 'success',
            'role' => $role
        ], 201);
    }

    public function delete($period_id, $id)
    {
        $role = Role::where('id', $id)->where('IsDeleted', false)->where('TenantId', $period_id)->first();

        if (empty($role)) {
            return response()->json([
                'status' => 'error',
                'msg' => 'The role does not exist'
            ], 404);
        }

        Permission::where('roleId', $id)->where('TenantId', $period_id)->delete();

        $role->IsDeleted = true;
        $role->DeleterUserId = Auth::id();
        $role->DeletionTime = now()->format('Y-m-d H:i:s');
        $role->save();

        return response()->json([
            'status' => 'success',
            'role' => $role
        ]);
    }

    public function get($period_id, $id)
    {
        $roleExist = Role::where('id', $id)->where('IsDeleted', false)->where('TenantId', $period_id)->get();

        if (empty($roleExist)) {
            return response()->json([
                'status' => 'error',
                'msg' => 'The role does not exist'
            ], 404);
        }

        $role = Role::where('IsDeleted', false)->where('TenantId', $period_id)->findOrFail($id);
        $role->permissions;

        return response()->json([
            'status' => 'success',
            'role' => $role
        ]);
    }

    public function getAll($period_id)
    {
        $roles = Role::where('IsDeleted', false)->where('TenantId', $period_id)->get();

        $data = [];

        foreach ($roles as $role) {
            $permissions = $role->permissions;
            $data[] = [
                'id' => $role->id,
                'name' => $role->name,
                'permissions' => $permissions
            ];
        }

        // $roles = Role::where('IsDeleted', false)->paginate(5);

        $permissions = Permission::distinct('name')
            ->whereNotIn('id', $roles->pluck('name'))
            ->where('TenantId', $period_id)
            ->get(['name']);

        $count = count($roles);

        return response()->json([
            'status' => 'success',
            'maxCount' => $count,
            'roles' => $data,
            'all_permissions' => $permissions
        ]);
    }

    public function update(UpdateRoleRequest $request, $period_id, $id)
    {
        // Validar si el rol ya existe
        $roleExists = Role::where('name', $request->name)->where('IsDeleted', 0)->where('TenantId', $period_id)->exists();
        $permissions = $request->permissions;
        $roleSetting = Role::where('name', $request->name)->where('IsDeleted', 0)->where('TenantId', $period_id)->first();

        if ($roleExists && $roleSetting->id != $id) {
            $existing_permissions = $roleSetting->permissions()->whereIn('name', $permissions)->get();

            // Agregar solo los permisos que no existen
            $new_permissions = array_diff($permissions, $existing_permissions->pluck('name')->toArray());

            // Agregar nuevos permisos
            $permissionsToSave = [];
            foreach ($new_permissions as $permissionName) {
                $permissionsToSave[] = [
                    'CreatorUserId' => Auth::id(),
                    'TenantId' => $period_id,
                    'name' => $permissionName,
                    'roleId' => $roleSetting->id
                ];
            }

            if (!empty($permissionsToSave)) {
                // Inserta los nuevos permisos
                Permission::insert($permissionsToSave);

                $permissions = $roleSetting->permissions;

                return response()->json([
                    'status' => 'success',
                    'role' => $roleSetting
                ], 201);
            }

            // No hay cambios, ya que ni nuevos permisos ni eliminación de permisos
            return response()->json([
                'status' => 'info',
                'msg' => 'No changes made. The role and all permissions already exist.'
            ], 200);
        }

        $role = Role::where('id', $id)->where('IsDeleted', false)->where('TenantId', $period_id)->first();

        // Verificar si el rol no existe
        if (empty($role)) {
            return response()->json([
                'status' => 'error',
                'msg' => 'The role does not exist'
            ], 404);
        }

        // Obtener los permisos actuales del rol
        $currentPermissions = $role->permissions->pluck('name')->toArray();

        //  Obtener los permisos del request
        $permissions = $request->permissions;

        // Obtener los permisos que ya no están en el array del request pero eliminar de la base de datos
        $permissionsToRemove = array_diff($currentPermissions, $permissions);

        // Obtener los permisos nuevos que no estan la base de datos
        $new_permissions = array_diff($permissions, $role->permissions->pluck('name')->toArray());

        // Agregar los nuevos permisos
        foreach ($new_permissions as $permissionName) {
            Permission::create([
                'CreatorUserId' => Auth::id(),
                'TenantId' => $period_id,
                'name' => $permissionName,
                'roleId' => $role->id
            ]);
        }

        // Actualizar los nombres de los permisos modificados
        $permissionsToUpdate = array_intersect($currentPermissions, $permissions);
        foreach ($permissionsToUpdate as $permissionName) {
            $existingPermission = $role->permissions->where('name', $permissionName)->where('TenantId', $period_id)->first();
            $existingPermission->update(['name' => $permissionName]);
        }

        // Eliminar los permisos que ya no están en el array $permissions pero existen en la base de datos
        $role->permissions()->whereIn('name', $permissionsToRemove)->delete();

        $role->update(['name' => $request->name]);
        $permissions = $role->permissions;

        $roleReturn = Role::find($id);
        $roleReturn->permissions;


        return response()->json([
            'status' => 'success',
            'role' => $roleReturn
        ], 201);
    }

    public function revoke(Request $request, $id, $period_id)
    {
        // Verificar si el rol ya existe
        $role = Role::where('id', $id)->where('IsDeleted', false)->where('TenantId', $period_id)->first();

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
                Permission::where('name', $request->name)->where('roleId', $id)->where('TenantId', $period_id)->delete();
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
