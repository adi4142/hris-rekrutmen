<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicationStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $statusLabel;
    public $customMessage;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($application, $statusLabel, $customMessage = null)
    {
        $this->application = $application;
        $this->statusLabel = $statusLabel;
        $this->customMessage = $customMessage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Update Status Lamaran - ' . $this->application->jobVacancie->title)
            ->view('emails.application_status');
    }
}
