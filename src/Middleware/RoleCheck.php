<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-01-30
 * Time: 15:49
 */

namespace WebAppId\User\Middleware;


use Closure;
use Illuminate\Support\Facades\Auth;

class RoleCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param string $userRole
     * @return mixed
     */
    public function handle($request, Closure $next, $userRole = "member")
    {
        $roles = Auth::user()->roles;
        $access = false;
        
        $userRoles = explode('|', $userRole);
        if ($roles != null) {
            foreach ($roles as $role) {
                foreach ($userRoles as $userRole) {
                    if (strtolower($role->name) == $userRole) {
                        $access = true;
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