<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function submitContactForm(Request $request)
    {
        // Validate the received data
        $validatedData = $request->validate([
            'firstName' => 'required|max:255',
            'lastName' => 'required|max:255',
            'email' => 'required|email',
            'subject' => 'required|max:255',
            'message' => 'required',
            'rgpdConsent' => 'accepted', // Ensures that the RGPD checkbox is checked
        ]);

        // Preparing data for the email
        $emailContent = "Nom: " . $validatedData['firstName'] . "\n" .
                        "Prénom: " . $validatedData['lastName'] . "\n" .
                        "Email: " . $validatedData['email'] . "\n" .
                        "Sujet: " . $validatedData['subject'] . "\n" .
                        "Message: " . $validatedData['message'];

        // Sending the email
        Mail::raw($emailContent, function ($message) use ($validatedData) {
            $message->from($validatedData['email'], $validatedData['firstName'] . ' ' . $validatedData['lastName']);
            $message->to('contact@gigpulse.fr', 'Gigpulse');
            $message->subject('Nouveau message: ' . $validatedData['subject']);
        });

        // Response after sending the email
        return response()->json(['message' => 'Your message has been sent successfully.']);
    }
}
