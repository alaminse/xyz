<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\ContactMail;

class ContactController extends Controller
{
    /**
     * Display the contact form
     */
    public function index()
    {
        $faqs = Setting::where('title', 'faqs')->first();
        return view('frontend.settings.contact', compact('faqs'));
    }

    /**
     * Handle contact form submission
     */
    public function send(Request $request)
    {
        // Validate the form data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Prepare email data
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'message' => $request->message,
            ];

            // Send email to admin
            Mail::to(config('mail.admin_email', 'admin@example.com'))
                ->send(new ContactMail($data));

            // Optional: Send confirmation email to user
            // Mail::to($request->email)->send(new ContactConfirmationMail($data));

            return response()->json([
                'success' => true,
                'message' => 'Thank you for contacting us! We will get back to you soon.'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message. Please try again later.'
            ], 500);
        }
    }
}
