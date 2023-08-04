<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class CheckPermissionUsers
{
    public function handle(Request $request, Closure $next, $role_name, $permission)
    {
        $user = Auth::user();

        if (!$user) {
            abort(401, 'Unauthorized');
        }

        $role_user = $user->userRoles()->first();

        if (!$role_user) {
            abort(403, 'No tienes asignado un rol. Por favor contacta con un administrador.');
        }

        if ($role_user->role->name != $role_name) {
            abort(403, 'No tienes el rol requerido. Por favor contacta con un administrador.');
        }

        $hasPermission = $role_user->role->permissions()->where('name', $permission)->exists();

        if (!$hasPermission) {
            abort(403, 'No tienes el permiso requerido. Por favor contacta con un administrador.');
        }

        return $next($request);
    }
}