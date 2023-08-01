<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use App\Models\Permission;

class CheckPermissionUsers
{
    public function handle(Request $request, Closure $next, $role_name, $permission)
    {
        $user = Auth::user();

        if (!$user) {
            abort(401, 'Unauthorized');
        }

        $role_user = $user->userRoles()->first();

        if (!$role_user || $role_user->role->name !== $role_name) {
            abort(403, 'The user has no role. Please contact an administrator.');
        }

        $role_id = Permission::where('name', $permission)->where('roleId', $role_user->roleId)->value('roleId');

        if (!$role_id) {
            abort(403, 'The permission is invalid. Please contact an administrator.');
        }

        if ($role_id !== $role_user->roleId) {
            abort(403, 'The user has no permission. Please contact an administrator.');
        }

        return $next($request);
    }
}
