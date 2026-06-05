<?php
// app/Notifications/CustomVerifyEmail.php
// Optional: override the default verification email with branded template.
// If you're happy with Laravel's default email, skip this file.

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmail extends VerifyEmail
{
    protected function buildMailMessage($url): MailMessage
    {
        return (new MailMessage)
            ->subject('Verify Your DealsHood Email Address')
            ->greeting('Welcome to DealsHood! 👋')
            ->line('Thanks for signing up. Please verify your email address by clicking the button below.')
            ->action('Verify Email Address', $url)
            ->line('This link expires in **60 minutes**.')
            ->line('If you did not create an account, no action is needed — simply ignore this email.')
            ->salutation('The DealsHood Team');
    }
}