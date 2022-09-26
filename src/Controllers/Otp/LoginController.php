<?php

namespace WebAppId\User\Controllers\Otp;

use WebAppId\SmartResponse\Response;
use WebAppId\SmartResponse\SmartResponse;
use WebAppId\User\Services\OTPRequestService;

class LoginController
{
    function __invoke(string $token, OTPRequestService $OTPRequestService, SmartResponse $smartResponse, Response $response)
    {
        $user = app()->call([$OTPRequestService, 'checkOtp'], compact('token'));
        if (!$user->status) {
            return $smartResponse->requestDenied($response);
        }

        $response->setData($user->activationKey);
        return $smartResponse->success($response);
    }
}
