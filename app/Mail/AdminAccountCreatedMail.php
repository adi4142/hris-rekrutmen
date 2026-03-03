<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminAccountCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $email;
    public $password;
    public $loginUrl;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userName, $email, $password)
    {
        $this->userName = $userName;
        $this->email = $email;
        $this->password = $password;
        $this->loginUrl = route('login');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Akun HRIS Anda Telah Dibuat')
                    ->view('emails.admin-account-created');
    }
}
