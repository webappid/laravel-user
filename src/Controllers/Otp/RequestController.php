<?php

namespace WebAppId\User\Controllers\Otp;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mime\Part\TextPart;
use WebAppId\SmartResponse\Response;
use WebAppId\SmartResponse\SmartResponse;
use WebAppId\User\Mail\OTPLoginLink;
use WebAppId\User\Services\OTPRequestService;

class RequestController
{
    function __invoke(Request $request, OTPRequestService $OTPRequestService, SmartResponse $smartResponse, Response $response)
    {
        $email = $request['email'];
        $result = app()->call([$OTPRequestService, 'getOtpRequest'], compact('email'));
        if ($result->status) {
            Mail::to($email)->send(new OTPLoginLink(['token' => $result->otp, 'url' => config('app.url') . '/otp/login/']));
            $result->otp = '';
        }else{
            $result->status = true;
        }
        return $smartResponse->success($response);
    }
}
