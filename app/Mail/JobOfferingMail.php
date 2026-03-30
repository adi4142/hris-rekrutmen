<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobOfferingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $acceptUrl;
    public $rejectUrl;
    public $negotiateUrl;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($application)
    {
        $this->application = $application;
        
        // Generate signed URLs for acceptance, rejection and negotiation
        $this->acceptUrl = \Illuminate\Support\Facades\URL::signedRoute('offering.respond', [
            'application_id' => $application->application_id,
            'response' => 'accept'
        ]);
        
        $this->rejectUrl = \Illuminate\Support\Facades\URL::signedRoute('offering.respond', [
            'application_id' => $application->application_id,
            'response' => 'reject'
        ]);

        $this->negotiateUrl = \Illuminate\Support\Facades\URL::signedRoute('offering.respond', [
            'application_id' => $application->application_id,
            'response' => 'negotiate'
        ]);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->subject('Offering Letter - ' . $this->application->jobVacancie->title)
            ->view('emails.job_offering');

        if ($this->application->offering_letter_file && \Storage::disk('public')->exists($this->application->offering_letter_file)) {
            $email->attachFromStorageDisk('public', $this->application->offering_letter_file, 'Offering_Letter_' . $this->application->offering_letter_no . '.pdf', [
                'mime' => 'application/pdf',
            ]);
        }

        return $email;
    }
}
