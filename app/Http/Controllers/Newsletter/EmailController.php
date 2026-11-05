<?php

namespace App\Http\Controllers\Newsletter;

use App\Actions\Hoi\Email\CreateUserInHoiEmailProject;
use App\Actions\Hoi\Email\DeleteUserFromHoiEmailProject;
use App\Http\Controllers\Controller;
use App\Models\Admincp\Consumers\Consumers;
use App\Models\Admincp\Consumers\ConsumersHoiToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class EmailController extends Controller
{
    protected function validator(Request $request)
    {
        return Validator::make($request->input(), [
            'email' => ['required', 'email', 'max:191'],
            'consent' => ['required', 'accepted'],
        ], [
            'email.required' => 'Lūdzu, ievadiet savu e-pasta adresi.',
            'email.email' => 'Lūdzu, ievadiet korektu e-pasta adresi.',
            'consent.required' => 'Lūdzu, apstipriniet piekrišanu jaunumu saņemšanai.',
            'consent.accepted' => 'Lūdzu, apstipriniet piekrišanu jaunumu saņemšanai.',
        ]);
    }

    /**
     * Subscribe an e-mail to newsletter & Hoi.fm e-mail project.
     *
     * @param  Request $request
     * @return RedirectResponse|JsonResponse
     */
    public function index(Request $request): RedirectResponse|JsonResponse
    {
        // Honeypot check (if filled by a bot, fail silently)
        if ($request->filled('website_url')) {
            return $this->respondSuccess($request, 'Paldies! Jūsu e-pasts ir veiksmīgi reģistrēts jaunumu saņemšanai.');
        }

        // CAPTCHA verification
        $captchaInput = trim((string) $request->input('captcha'));
        $captchaExpected = (string) session('captcha_code');

        if ($captchaInput === '' || $captchaExpected === '' || strcasecmp($captchaInput, $captchaExpected) !== 0) {
            return $this->respondError($request, 'Nepareizs drošības kods! Lūdzu, ievadiet attēlā redzamo kodu vēlreiz.', 'captcha');
        }

        $data = $this->validator($request);

        if ($data->fails()) {
            return $this->respondError($request, $data->errors()->first(), 'email');
        }

        // Clear used CAPTCHA code once validation succeeds
        session()->forget('captcha_code');

        $validated = $data->validated();
        $email = strtolower(trim($validated['email']));
        $hoiToken = Str::random(6);
        $fingerprint = $request->cookie('fingerprint');

        $existing = Consumers::where('email', $email)->first();

        if ($existing) {
            $consumer = $this->update($email, $hoiToken, $fingerprint);

            try {
                DeleteUserFromHoiEmailProject::run($consumer->email);
                CreateUserInHoiEmailProject::run($consumer);
            } catch (\Throwable $e) {
                report($e);
            }
        } else {
            $consumer = $this->create($email, $hoiToken, $fingerprint);

            ConsumersHoiToken::create([
                'consumer_id' => $consumer->id,
                'hoi_token' => $hoiToken,
            ]);

            try {
                CreateUserInHoiEmailProject::run($consumer);
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return $this->respondSuccess($request, 'Paldies! Jūsu e-pasts ir veiksmīgi reģistrēts jaunumu saņemšanai.');
    }

    protected function respondSuccess(Request $request, string $message): RedirectResponse|JsonResponse
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'ok',
                'message' => $message,
            ]);
        }

        return redirect()
            ->back()
            ->with('newsletter_open', true)
            ->with('newsletter_success', $message);
    }

    protected function respondError(Request $request, string $message, string $field = 'email'): RedirectResponse|JsonResponse
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'error',
                'field' => $field,
                'message' => $message,
            ], 422);
        }

        return redirect()
            ->back()
            ->withInput($request->only('email', 'consent'))
            ->with('newsletter_open', true)
            ->with('newsletter_error', $message);
    }

    /**
     * Create a new consumer record.
     */
    protected function create(string $email, string $hoiToken, ?string $fingerprint): Consumers
    {
        return Consumers::create([
            'email' => $email,
            'country' => substr((string) App::getLocale(), 0, 2) ?: 'lv',
            'hoi_token' => $hoiToken,
            'fingerprint' => $fingerprint ? substr($fingerprint, 0, 20) : null,
            'visitor_id' => (int) (request()->cookie('visitor_id') ?: 0),
            'type' => 'newsletter',
            'subscribed' => 1,
            'email_notifications' => 1,
            'utm_source' => request()->cookie('utm_source'),
            'utm_campaign' => request()->cookie('utm_campaign'),
            'utm_medium' => request()->cookie('utm_medium'),
            'utm_content' => request()->cookie('utm_content'),
            'gclid' => request()->cookie('gclid'),
            'ip' => (string) (request()->ip() ?: '127.0.0.1'),
        ]);
    }

    /**
     * Update an existing consumer record.
     */
    protected function update(string $email, string $hoiToken, ?string $fingerprint): Consumers
    {
        Consumers::where('email', $email)->update([
            'country' => substr((string) App::getLocale(), 0, 2) ?: 'lv',
            'hoi_token' => $hoiToken,
            'fingerprint' => $fingerprint ? substr($fingerprint, 0, 20) : null,
            'visitor_id' => (int) (request()->cookie('visitor_id') ?: 0),
            'subscribed' => 1,
            'email_notifications' => 1,
            'utm_source' => request()->cookie('utm_source'),
            'utm_campaign' => request()->cookie('utm_campaign'),
            'utm_medium' => request()->cookie('utm_medium'),
            'utm_content' => request()->cookie('utm_content'),
            'gclid' => request()->cookie('gclid'),
            'ip' => (string) (request()->ip() ?: '127.0.0.1'),
        ]);

        return Consumers::where('email', $email)->firstOrFail();
    }
}
