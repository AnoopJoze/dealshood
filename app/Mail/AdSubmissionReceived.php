<?php

namespace App\Mail;

use App\Models\AdSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdSubmissionReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public AdSubmission $submission) {}

    public function build()
    {
        return $this->subject('We received your ad — ' . setting('site_name', 'DealsHood'))
            ->html(view('emails.ad-submission-received', ['submission' => $this->submission])->render());
    }
}