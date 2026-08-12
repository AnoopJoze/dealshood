<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Handle a contact-form submission: validate, email the admin,
     * and redirect back with a success message.
     */
    public function send(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:120',
            'email'   => 'required|email|max:180',
            'subject' => 'required|string|max:120',
            'message' => 'required|string|max:4000',
        ]);

        $adminEmail = config('mail.admin_address', env('ADMIN_EMAIL'));

        // Sending must never 500 the visitor if SMTP is misconfigured —
        // log the failure and still confirm receipt.
        if ($adminEmail) {
            try {
                Mail::raw(
                    "New contact form submission\n\n"
                    . "Name: {$data['name']}\n"
                    . "Email: {$data['email']}\n"
                    . "Subject: {$data['subject']}\n\n"
                    . "Message:\n{$data['message']}\n",
                    function ($mail) use ($adminEmail, $data) {
                        $mail->to($adminEmail)
                             ->replyTo($data['email'], $data['name'])
                             ->subject('[Contact] ' . $data['subject']);
                    }
                );
            } catch (\Throwable $e) {
                Log::error('Contact form email failed: ' . $e->getMessage(), [
                    'email'   => $data['email'],
                    'subject' => $data['subject'],
                ]);
            }
        }

        return back()->with('success', 'Thanks for reaching out! We\'ll get back to you soon.');
    }
}
