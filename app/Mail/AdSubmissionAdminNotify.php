<?php

namespace App\Mail;

use App\Models\AdSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdSubmissionAdminNotify extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public AdSubmission $submission) {}

    public function build()
    {
        return $this->subject('New Ad Submission — ' . $this->submission->title)
            ->html(view('emails.ad-submission-admin', ['submission' => $this->submission])->render());
    }
}