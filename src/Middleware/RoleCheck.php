<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-01-30
 * Time: 15:49
 */

namespace WebAppId\User\Middleware;


use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

class RoleCheck
{
    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @param string $userRole
     * @return mixed|void
     * @throws AuthenticationException
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