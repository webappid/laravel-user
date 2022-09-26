<?php

namespace WebAppId\User\Controllers\Otp;

use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mime\Part\TextPart;
use WebAppId\SmartResponse\Response;
use WebAppId\SmartResponse\SmartResponse;
use WebAppId\User\Mail\OTPLoginLink;
use WebAppId\User\Services\OTPRequestService;

class RequestController
{
    function __invoke(string $email, OTPRequestService $OTPRequestService, SmartResponse $smartResponse, Response $response)
    {
        $result = app()->call([$OTPRequestService, 'getOtpRequest'], compact('email'));
        Mail::to($email)->send(new OTPLoginLink(['token' => $result->otp, 'url' => config('app.url') . '/otp/login/']));
        return $smartResponse->success($response);
    }
}
