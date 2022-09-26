<?php

namespace WebAppId\User\Repositories;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;


class OTPRequestRepository
{
    /**
     * @param string $email
     * @return string
     */
    function generateOTP(string $email): string
    {
        $currentOTP = Cache::get('otp_email_' . $email);
        if ($currentOTP !== null) {
            Cache::forget('otp_email_' . $email);
            Cache::forget('otp_token_' . $currentOTP);
        }
        $otp = Str::uuid()->toString();
        Cache::put('otp_email_' . $email, $otp, $seconds = (1 * 60 * 30));
        Cache::put('otp_token_' . $otp, $email, $seconds = (1 * 60 * 30));
        return $otp;
    }

    /**
     * @param $email
     * @return void
     */
    function destroyByEmail($email): void
    {
        $currentOTP = Cache::get('otp_email_' . $email);
        if ($currentOTP !== null) {
            Cache::forget('otp_email_' . $email);
            Cache::forget('otp_token_' . $currentOTP);
        }
    }

    /**
     * @param $token
     * @return void
     */
    function destroyByToken($token): void
    {
        $currentEmail = Cache::get('otp_token_' . $token);
        if ($currentEmail !== null) {
            Cache::forget('otp_email_' . $currentEmail);
            Cache::forget('otp_token_' . $token);
        }
    }

    /**
     * @param $token
     * @return string|null
     */
    function otpChecked($token): ?string
    {
        return Cache::get('otp_token_' . $token);
//        if ($currentEmail !== null) {
//            Cache::forget('otp_email_' . $currentEmail);
//            Cache::forget('otp_token_' . $token);
//            return true;
//        } else {
//            return false;
//        }
    }
}
