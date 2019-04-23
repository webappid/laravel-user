<?php
/**
 * Created by PhpStorm.
 * User: Fadlika_N
 * Date: 3/8/2019
 * Time: 9:10 AM
 */

namespace WebAppId\User\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

class PermissionCheck
{
    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @param string $rolePermission
     * @return mixed|void
     * @throws AuthenticationException
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
                        if (strtolower($permission->name) == strtolower($rolePermission)) {
                            $access = true;
                        }
                    }
                }
            }
        }
        if ($access) {
            return $next($request);
        } else {
            $guards = [];
            $acceptRequest = $request->headers->get('accept');
            if ($acceptRequest == 'application/json') {
                throw new AuthenticationException(
                    'Unauthenticated.', $guards
                );
            } else {
                return abort(403);
            }
        }
    }
}