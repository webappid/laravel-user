<?php
/**
 * Created by PhpStorm.
 * User: Fadlika_N
 * Date: 3/8/2019
 * Time: 9:10 AM
 */

namespace WebAppId\User\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class PermissionCheck
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string $rolePermission
     * @return mixed
     */
    public function handle($request, Closure $next, $rolePermission = "allaccess")
    {
        $roles = Auth::user()->roles;
        $access = false;

        foreach($roles as $role) {
            $permissions = $role->permissions;
            $rolePermissions = explode('|', $rolePermission);
            if ($permissions != null) {
                foreach ($permissions as $permission) {
                    foreach ($rolePermissions as $rolePermission) {
                        if (strtolower($permission->name) == $rolePermission) {
                            $access = true;
                        }
                    }
                }
            }
        }
        if ($access) {
            return $next($request);
        } else {
            return abort(403);
        }
    }
}