<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\AssignRoleRequest;
use App\models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\UserRole;

class UserController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api');
        $this->middleware("check.permissions:Admin,pages.user", ['only'=>['get', 'getAll']]);
        $this->middleware("check.permissions:Admin,pages.user.modify", ['only'=>['create', 'update']]);
        $this->middleware("check.permissions:Admin,pages.user.delete", ['only'=>['delete']]);
    }

    public function create(CreateUserRequest $request)
    {
        $emailExists = User::where('email', $request->input('email'))->exists();
        $usernameExists = User::where('username', $request->input('username'))->exists();

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
            'CreatorUserId' => Auth::id()
        ]);

        $user->save();

        return response()->json([
            'status' => 'success',
            'data' => $user
        ],201);
    }

    public function delete($id)
    {
        $user = User::where('id', $id)->where('IsDeleted', false)->first();

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

        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function get($id)
    {
        $userExist = DB::table('users')->where('id', $id)->get();

        if (empty($userExist)) {
            return response()->json([
                'status' => 'error',
                'msg' => 'The user does not exist'
            ], 404);
        }

        $user = User::findOrFail($id);

        $roles = $user->userRoles->pluck('role.name');

        $data[] = [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'name' => $user->name,
                'surname' => $user->surname,
                'phoneNumber' => $user->phoneNumber,
                'profilePicture' => $user->profilePicture,
                'roles' => $roles
            ];

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function getAll()
    {
        $users = User::all();

        $data = [];

        foreach ($users as $user) {
            $roles = $user->userRoles->pluck('role.name');
            $data[] = [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'name' => $user->name,
                'surname' => $user->surname,
                'phoneNumber' => $user->phoneNumber,
                'profilePicture' => $user->profilePicture,
                'roles' => $roles
            ];
        }

        $count = count($users);

        return response()->json([
            'status' => 'success',
            'maxCount' => $count,
            'data' => $data
        ]);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::where('id', $id)->where('IsDeleted', false)->first();

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

        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }


    public function assign(AssignRoleRequest $request){

        $user_exist = User::where('id', $request->userId)->where('IsDeleted', false)->first();
        $role_exist = Role::where('id', $request->roleId)->where('IsDeleted', false)->first();
        $permission_exists = Permission::where('id', $request->permissionId)->where('IsDeleted', false)->first();


        if (empty($user_exist) || empty($role_exist)) {
            return response()->json([
                'status' => 'error',
                'msg' => empty($user_exist) && empty($role_exist) ? 'The user and role does not exist' : (empty($user_exist) ? 'The user does not exists' : 'The role does not exists')
            ], 400);
        }

        $user_roles = UserRole::where('userId', $request->userId)->pluck('roleId')->toArray();

        $user = Auth::user();

        foreach ($user->userRoles as $user_role) {
            if ($user_role->roleId == $request->roleId) {
                $role_name = $user_role->role->name;
                return response()->json([
                    'status' => 'error',
                    'msg' => 'The role '.$role_name.' is already assigned to the user'
                ], 400);
            }
        }

        $assigned_role = new UserRole([
            "userId" => $request->userId,
            "roleId" => $request->roleId
        ]);

        $assigned_role->save();

        $roles = Role::findOrFail($request->roleId);
        $permissions = $roles->permissions;

        foreach ($permissions as $permission) {
            $permission->update([
                'userId' => $request->userId
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $assigned_role
        ]);
    }

    public function unassign(AssignRoleRequest $request)
    {
        $user_exist = User::where('id', $request->userId)->where('IsDeleted', false)->first();
        $role_exist = Role::where('id', $request->roleId)->where('IsDeleted', false)->first();

        if (empty($user_exist) || empty($role_exist)) {
            return response()->json([
                'status' => 'error',
                'msg' => empty($user_exist) && empty($role_exist) ? 'The user and role do not exist' : (empty($user_exist) ? 'The user does not exist' : 'The role does not exist')
            ], 400);
        }

        $user_role = UserRole::where('userId', $request->userId)->where('roleId', $request->roleId)->first();

        $role = Role::findOrFail($request->roleId);

        if (empty($user_role)) {
            return response()->json([
                'status' => 'error',
                'msg' => 'The role '.$role->name.' is not assigned to the user'
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
            'msg' => 'The role '.$role->name.' was successfully unassigned from the user'
        ]);
    }
}
