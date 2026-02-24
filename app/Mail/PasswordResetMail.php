<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $verifyUrl;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userName, $verifyUrl)
    {
        $this->userName = $userName;
        $this->verifyUrl = $verifyUrl;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Verifikasi Reset Password - HRIS System')
                    ->view('emails.password-reset');
    }
}
