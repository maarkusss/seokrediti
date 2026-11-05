<?php

namespace App\Http\Controllers;

use App\Models\Admincp\Settings;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    public function sendMail(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'name' => 'required',
            'question' => 'required',
        ]);

        if ($request->filled('captcha') && session('captcha_code')) {
            if (strtolower((string) $request->captcha) !== strtolower((string) session('captcha_code'))) {
                return redirect()->back()->withInput()->withErrors(['captcha' => 'Nepareizs drošības kods!']);
            }
        }

        $to = config('mail.from.address', 'info@eirokrediti.lv');
        $subject = 'Contact Form from website';
        $message = "Name: " . $data['name'] . "\n\n" . $data['question'];
        $headers = 'From: ' . $data['email'] . "\r\n" .
            'Reply-To: ' . $data['email'] . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        @mail($to, $subject, $message, $headers);

        return redirect()->back()->with('success', __('Message sent successfully!'));
    }
}
