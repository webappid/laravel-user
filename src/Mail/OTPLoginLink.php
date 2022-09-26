<?php

namespace WebAppId\User\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OTPLoginLink extends Mailable
{
    use Queueable, SerializesModels;

    private $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($payload)
    {
        //
        $this->data = $payload;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('OTP Login Link ' . date('YmdHis') )->view('user::mail')->with(['token' => $this->data['token'], 'url' => $this->data['url']]);
    }
}
