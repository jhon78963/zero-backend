<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignRoleRequest;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\AcademicPeriod;
use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.permissions:Admin,pages.user')->only(['index', 'getAll', 'get']);
        $this->middleware('check.permissions:Admin,pages.user.modify')->only(['create', 'update']);
        $this->middleware('check.permissions:Admin,pages.user.delete')->only(['delete']);
        $this->middleware('check.permissions:Admin,pages.user.assign')->only(['assign']);
    }

    public function index($period_name)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();
        return view('access.user.index', compact('period'));
    }

    public function create(CreateUserRequest $request, $period_id)
    {
        $emailExists = User::where('email', $request->input('email'))->where('IsDeleted', false)->where('TenantId', $period_id)->exists();
        $usernameExists = User::where('username', $request->input('username'))->where('IsDeleted', false)->where('TenantId', $period_id)->exists();

        if ($emailExists || $usernameExists) {
            return response()->json([
                'status' => 'error',
                'msg' => $emailExists && $usernameExists ? 'The email and username already exist' : ($emailExists ? 'The email already exists' : 'The username already exists')
            ], 400);
        }

        $user = new User([
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'surname' => $request->input('surname'),
            'password' => Hash::make($request->input('password')),
            'phoneNumber' => $request->input('phoneNumber'),
            'profilePicture' => '/assets/img/avatars/1.png',
            'CreatorUserId' => Auth::id(),
            'TenantId' => $period_id
        ]);

        $user->save();

        $user->userRoles->pluck('role.name');

        return response()->json([
            'status' => 'success',
            'user' => $user
        ], 201);
    }

    public function delete($period_id, $id)
    {
        $user = User::where('id', $id)->where('IsDeleted', false)->where('TenantId', $period_id)->first();

        if (empty($user)) {
            return response()->json([
                'status' => 'error',
                'msg' => 'The user does not exist'
            ], 404);
        }

        $user->IsDeleted = true;
        $user->DeleterUserId = Auth::id();
        $user->DeletionTime = now()->format('Y-m-d H:i:s');
        $user->save();

        UserRole::where('userId', $user->id)->delete();

        return response()->json([
            'status' => 'success',
            'user' => $user
        ]);
    }

    public function get($period_id, $id)
    {
        $userExist = DB::table('users')->where('id', $id)->where('IsDeleted', false)->where('TenantId', $period_id)->first();

        if (empty($userExist)) {
            return response()->json([
                'status' => 'error',
                'msg' => 'The user does not exist'
            ], 404);
        }

        $user = User::findOrFail($id);

        $roles = $user->userRoles->pluck('role.name');
        $role_id = $user->userRoles->pluck('role.id');

        $data = [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'name' => $user->name,
            'surname' => $user->surname,
            'phoneNumber' => $user->phoneNumber,
            'profilePicture' => $user->profilePicture,
            'roles' => $roles,
            'role_id' => $role_id,
        ];

        return response()->json([
            'status' => 'success',
            'user' => $data
        ]);
    }

    public function getAll($period_id)
    {
        $users = User::where('IsDeleted', false)->where('TenantId', $period_id)->get();

        $data = [];

        foreach ($users as $user) {
            $data[] = [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'name' => $user->name,
                'surname' => $user->surname,
                'phoneNumber' => $user->phoneNumber,
                'profilePicture' => $user->profilePicture,
                'roles' => $user->userRoles->pluck('role.name')
            ];
        }

        $count = count($users);

        return response()->json([
            'status' => 'success',
            'maxCount' => $count,
            'users' => $data
        ]);
    }

    public function update(UpdateUserRequest $request, $period_id, $id)
    {
        $user = User::where('id', $id)->where('IsDeleted', false)->where('TenantId', $period_id)->first();

        if (empty($user)) {
            return response()->json([
                'status' => 'error',
                'msg' => 'The user does not exist'
            ], 404);
        }

        $user->name = $request->input('name');
        $user->surname = $request->input('surname');
        $user->phoneNumber = $request->input('phoneNumber');
        $user->LastModificationTime = now()->format('Y-m-d H:i:s');
        $user->LastModifierUserId = Auth::id();
        $user->save();

        $user->userRoles->pluck('role.name');

        return response()->json([
            'status' => 'success',
            'user' => $user
        ]);
    }

    public function getRoles($period_id, $id)
    {
        $user = User::where('id', $id)->where('IsDeleted', false)->where('TenantId', $period_id)->get();

        if (empty($user)) {
            return response()->json([
                'status' => 'error',
                'msg' => 'The user does not exist'
            ], 404);
        }

        $roles = $user->userRoles->pluck('role.name');
        $data[] = [
            'id' => $user->id,
            'roles' => $roles
        ];

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function unassign(AssignRoleRequest $request, $period_id)
    {
        $user_exist = User::where('id', $request->userId)->where('IsDeleted', false)->where('TenantId', $period_id)->first();
        $role_exist = Role::where('id', $request->roleId)->where('IsDeleted', false)->where('TenantId', $period_id)->first();

        if (empty($user_exist) || empty($role_exist)) {
            return response()->json([
                'status' => 'error',
                'msg' => empty($user_exist) && empty($role_exist) ? 'The user and role do not exist' : (empty($user_exist) ? 'The user does not exist' : 'The role does not exist')
            ], 400);
        }

        $user_role = UserRole::where('userId', $request->userId)->where('roleId', $request->roleId)->where('TenantId', $period_id)->first();

        $role = Role::findOrFail($request->roleId);

        if (empty($user_role)) {
            return response()->json([
                'status' => 'error',
                'msg' => 'The role ' . $role->name . ' is not assigned to the user'
            ], 400);
        }

        $user_role->delete();

        $roles = Role::findOrFail($request->roleId);
        $permissions = $roles->permissions;

        foreach ($permissions as $permission) {
            $permission->update([
                'userId' => null
            ]);
        }

        return response()->json([
            'status' => 'success',
            'msg' => 'The role ' . $role->name . ' was successfully unassigned from the user'
        ]);
    }

    public function assign(AssignRoleRequest $request, $period_id)
    {
        UserRole::where('userId', $request->userId)->where('TenantId', $period_id)->delete();

        $user_exist = User::where('id', $request->userId)->where('IsDeleted', false)->where('TenantId', $period_id)->first();
        $role_exist = Role::where('id', $request->roleId)->where('IsDeleted', false)->where('TenantId', $period_id)->first();
        // $permission_exists = Permission::where('id', $request->permissionId)->first();


        if (empty($user_exist) || empty($role_exist)) {
            return response()->json([
                'status' => 'error',
                'msg' => empty($user_exist) && empty($role_exist) ? 'The user and role does not exist' : (empty($user_exist) ? 'The user does not exists' : 'The role does not exists')
            ], 400);
        }

        // $user_roles = UserRole::where('userId', $request->userId)->pluck('roleId')->toArray();

        $user = User::findOrFail($request->userId);

        foreach ($user->userRoles as $user_role) {
            if ($user_role->roleId == $request->roleId) {
                $role_name = $user_role->role->name;
                return response()->json([
                    'status' => 'error',
                    'msg' => 'The role ' . $role_name . ' is already assigned to the user'
                ], 400);
            }
        }

        $assigned_role = new UserRole([
            "userId" => $request->userId,
            "roleId" => $request->roleId,
            'CreatorUserId' => Auth::id(),
            'TenantId' => $period_id
        ]);

        $assigned_role->save();

        $roles = Role::findOrFail($request->roleId);
        $permissions = $roles->permissions;

        foreach ($permissions as $permission) {
            $permission->update([
                'userId' => $request->userId
            ]);
        }

        $userFinal = User::findOrFail($assigned_role->userId);
        $userFinal->userRoles->pluck('role.name');

        return response()->json([
            'status' => 'success',
            'user' => $userFinal
        ]);
    }
}
