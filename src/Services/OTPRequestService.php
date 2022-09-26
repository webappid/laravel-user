<?php

namespace WebAppId\User\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use WebAppId\User\Repositories\OTPRequestRepository;
use WebAppId\User\Services\Requests\UserServiceRequest;
use WebAppId\User\Services\Responses\OTPRequestResponse;

class OTPRequestService
{
    /**
     * @param OTPRequestResponse $OTPRequestResponse
     * @param OTPRequestRepository $OTPRequestRepository
     * @param string $email
     * @return OTPRequestResponse
     */
    function getOtpRequest(OTPRequestResponse $OTPRequestResponse, OTPRequestRepository $OTPRequestRepository, string $email): OTPRequestResponse
    {
        $otp = app()->call([$OTPRequestRepository, 'generateOTP'], compact('email'));
        if ($otp !== null) {
            $OTPRequestResponse->otp = $otp;
            $OTPRequestResponse->status = true;
            $OTPRequestResponse->message = 'Success';
        } else {
            $OTPRequestResponse->status = false;
        }
        return $OTPRequestResponse;
    }

    /**
     * @param OTPRequestResponse $OTPRequestResponse
     * @param OTPRequestRepository $OTPRequestRepository
     * @param string $token
     * @return OTPRequestResponse
     */
    function checkOtp(UserService          $userService,
                      UserServiceRequest   $userServiceRequest,
                      OTPRequestResponse   $OTPRequestResponse,
                      OTPRequestRepository $OTPRequestRepository,
                      string               $token): OTPRequestResponse
    {
        $email = app()->call([$OTPRequestRepository, 'otpChecked'], compact('token'));
        if ($email !== null) {
            $currentUser = app()->call([$userService, 'getByEmail'], compact('email'));
            if (!$currentUser->status) {
                $userServiceRequest->email = $email;
                $userServiceRequest->name = $email;
                $userServiceRequest->status_id = 2;
                $userServiceRequest->password = Str::uuid();
                $userRoleList = [1];
                app()->call([$userService, 'store'], compact('userServiceRequest', 'userRoleList'));
            }
            $isForce = true;
            $password = '';
            $user = app()->call([$userService, 'login'], compact('email', 'isForce', 'password'));
            $OTPRequestResponse->activationKey = $user->activationKey;
            $OTPRequestResponse->status = true;
            $OTPRequestResponse->message = 'Success';
            app()->call([$OTPRequestRepository, 'destroyByEmail'], compact('email'));
        } else {
            $OTPRequestResponse->status = false;
        }
        return $OTPRequestResponse;
    }
}
