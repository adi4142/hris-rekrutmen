<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $roleName;
    public $token;
    public $verifyUrl;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userName, $roleName, $token)
    {
        $this->userName = $userName;
        $this->roleName = $roleName;
        $this->token = $token;
        $this->verifyUrl = route('emails.verify.link', ['token' => $token]);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Verifikasi Email Anda - HRIS System')
                    ->view('emails.email-verification-link');
    }
}
