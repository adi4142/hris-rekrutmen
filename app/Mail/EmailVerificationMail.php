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
    public $verificationCode;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userName, $roleName, $verificationCode)
    {
        $this->userName = $userName;
        $this->roleName = $roleName;
        $this->verificationCode = $verificationCode;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Verifikasi Email Anda - HRIS System')
                    ->view('emails.email-verification');
    }
}
