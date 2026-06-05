<?php
// app/Http/Controllers/ContactController.php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:100',
            'subject' => 'required|string|max:100',
            'message' => 'required|string|min:10|max:2000',
        ]);

        // Send email to admin
        Mail::send('emails.contact', $data, function ($mail) use ($data) {
            $mail->to(setting('contact_email', config('mail.from.address')))
                 ->replyTo($data['email'], $data['name'])
                 ->subject('[DealsHood Contact] ' . $data['subject']);
        });

        return back()->with('success',
            'Your message has been sent! We\'ll get back to you within 24 hours.'
        );
    }
}
