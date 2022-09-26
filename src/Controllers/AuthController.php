<?php

namespace WebAppId\User\Controllers;

use App\Http\Controllers\Controller;
use WebAppId\SmartResponse\Response;
use WebAppId\SmartResponse\SmartResponse;
use WebAppId\User\Services\UserService;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request, Response $response, SmartResponse $smartResponse, UserService $userService)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return $smartResponse->requestDenied($response);
        }

        $user = app()->call([$userService, 'login'], ['email' => $request['email'], 'password' => $request['password']]);

        if(!$user->status){
            return $smartResponse->requestDenied($response);
        }

        $response->setData($user->activationKey);
        return $smartResponse->success($response);
    }
    public function logout(SmartResponse $smartResponse, Response $response){
        auth()->user()->tokens()->delete();
        $response->setMessage('Logout Success');
        return $smartResponse->success($response);
    }

    public function requestToken(Request $request, Response $response, SmartResponse $smartResponse, UserService $userService){
        if (!Auth::attempt($request->only('email'))) {
            return $smartResponse->requestDenied($response);
        }
    }
}
