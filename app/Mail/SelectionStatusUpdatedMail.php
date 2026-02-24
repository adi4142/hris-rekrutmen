<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SelectionStatusUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $applicantName;
    public $jobTitle;
    public $selectionStage;
    public $status;
    public $selectionDate;
    public $notes;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($applicantName, $jobTitle, $selectionStage, $status, $selectionDate, $notes)
    {
        $this->applicantName = $applicantName;
        $this->jobTitle = $jobTitle;
        $this->selectionStage = $selectionStage;
        $this->status = $status;
        $this->selectionDate = $selectionDate;
        $this->notes = $notes;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Update Status Lamaran Pekerjaan')
            ->markdown('emails.selection_status_updated');
    }
}
