<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BatchInfoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $batch;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($application)
    {
        $this->application = $application;
        $this->batch = $application->batch;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Informasi Jadwal Seleksi - ' . $this->application->jobVacancie->title)
            ->view('emails.batch_info');
    }
}
