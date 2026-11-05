<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    public function sendMail(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|max:150',
            'name' => 'required|string|max:100',
            'question' => 'required|string|max:3000',
        ]);

        // Enforce CAPTCHA check whenever a CAPTCHA code has been generated in the user's session
        $expectedCaptcha = (string) session('captcha_code', '');
        $submittedCaptcha = trim((string) $request->input('captcha', ''));

        if ($expectedCaptcha !== '' || $request->has('captcha')) {
            if ($submittedCaptcha === '' || $expectedCaptcha === '' || strcasecmp($submittedCaptcha, $expectedCaptcha) !== 0) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['captcha' => 'Nepareizs drošības kods! Lūdzu, ievadiet attēlā redzamo kodu.']);
            }
            session()->forget('captcha_code');
        }

        $to = brand_email();
        $senderName = brand_sender_name();
        $encodedSender = '=?UTF-8?B?' . base64_encode($senderName) . '?=';
        $subject = '[' . $senderName . '] Ziņojums no kontaktformas';
        $message = "Vārds: " . $data['name'] . "\nE-pasts: " . $data['email'] . "\n\nZiņojums:\n" . $data['question'];
        $headers = 'From: ' . $encodedSender . ' <' . brand_email() . ">\r\n" .
            'Reply-To: ' . $data['email'] . "\r\n" .
            'Content-Type: text/plain; charset=UTF-8' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        @mail($to, $subject, $message, $headers);

        return redirect()->back()->with('success', __('Message sent successfully!'));
    }
}
