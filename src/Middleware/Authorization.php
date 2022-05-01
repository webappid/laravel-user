<?php

namespace WebAppId\User\Middleware;

use Closure;
use WebAppId\User\Repositories\RouteRepository;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class Authorization extends Middleware
{

    /**
     * @param Request $request
     * @param Closure $next
     * @param mixed ...$guards
     * @return mixed
     * @throws BindingResolutionException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $user = Auth::guard()->user();
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
            }
        }

        if ($user != null && $user->status_id == 5) {
            return $this->loginFirst();
        }
        $roles = [];
        if ($user != null) {
            foreach ($user->roles as $role) {
                $roles[] = $role->id;
            }
        } else {
            if (!$request->expectsJson()) {
                $this->unauthenticated($request, $guards);
            } else {
                $this->notAuthenticate();
            }
        }
        $route = $request->route()->getName();

        $routeRepository = app()->make(RouteRepository::class);

        $result = app()->call([$routeRepository, 'getRouteByRouteNameAndRole'], ['route' => $route, 'roles' => $roles]);

        if ($result != null) {
            return $next($request);
        }
        if (!$request->expectsJson()) {
            abort(403);
        } else {
            return $this->notAuthenticate();
        }
        return null;
    }

    protected function notAuthenticate()
    {
        $result = [
            "code" => "403",
            "message" => "You don't have grant access, please contact your administrator",
            "data" => [],
        ];
        $response = new Response();
        $response->setStatusCode(403);
        $response->withHeaders(["Content-Type" => "application/json"]);
        $response->setContent(json_encode($result));
        return $response;
    }

    protected function loginFirst()
    {
        $result = [
            "code" => "401",
            "message" => "Please login first",
            "data" => [],
        ];
        $response = new Response();
        $response->setStatusCode(401);
        $response->withHeaders(["Content-Type" => "application/json"]);
        $response->setContent(json_encode($result));
        return $response;
    }
}
