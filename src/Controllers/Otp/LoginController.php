<?php

namespace WebAppId\User\Controllers\Otp;

use Illuminate\Http\Request;
use WebAppId\SmartResponse\Response;
use WebAppId\SmartResponse\SmartResponse;
use WebAppId\User\Services\OTPRequestService;

class LoginController
{
    function __invoke(Request $request, OTPRequestService $OTPRequestService, SmartResponse $smartResponse, Response $response)
    {
        $user = app()->call([$OTPRequestService, 'checkOtp'], ["token" => $request['token']]);

        if (!$user->status) {
            return $smartResponse->requestDenied($response);
        }

        $response->setData(["token" => $user->activationKey]);
        return $smartResponse->success($response);
    }
}
