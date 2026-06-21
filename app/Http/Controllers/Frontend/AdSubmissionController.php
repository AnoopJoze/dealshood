<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\AdSubmissionAdminNotify;
use App\Mail\AdSubmissionReceived;
use App\Models\AdSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdSubmissionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:100',
            'email'            => 'required|email|max:100',
            'phone'            => 'nullable|string|max:20',
            'whatsapp'         => 'nullable|string|max:20',
            'title'            => 'required|string|max:255',
            'category_id'      => 'required|exists:categories,id',
            'locality_id'      => 'nullable|exists:localities,id',
            'description'      => 'nullable|string|max:2000',
            'company_name'     => 'nullable|string|max:255',
            'location'         => 'nullable|string|max:255',
            'offer_percentage' => 'nullable|numeric|min:0|max:100',
            'expiry_date'      => 'nullable|date|after:today',
            'images'           => 'nullable|array|max:5',
            'images.*'         => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $submission = AdSubmission::create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $submission->addMedia($image)->toMediaCollection('images');
            }
        }

        // Mail::to($submission->email)->send(new AdSubmissionReceived($submission));

        // $adminEmail = 'dealshood71@gmail.com';
        // Mail::to($adminEmail)->send(new AdSubmissionAdminNotify($submission));

        return response()->json([
            'success' => true,
            'message' => "Your ad has been submitted! We'll review it and get back to you.",
        ]);
    }
}
