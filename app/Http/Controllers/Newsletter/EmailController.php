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
    /**
     * Common disposable / throwaway e-mail domains used by spam bots.
     */
    protected const DISPOSABLE_DOMAINS = [
        'mailinator.com',
        '10minutemail.com',
        'guerrillamail.com',
        'tempmail.com',
        'temp-mail.org',
        'yopmail.com',
        'sharklasers.com',
        'trashmail.com',
        'getnada.com',
        'maildrop.cc',
        'fakeinbox.com',
        'dispostable.com',
        'mailnesia.com',
        'mohmal.com',
        'emailondeck.com',
        'throwawaymail.com',
    ];

    protected function validator(Request $request)
    {
        $emailRule = app()->runningUnitTests() ? 'email' : 'email:rfc,dns';

        return Validator::make($request->input(), [
            'email' => [
                'required',
                $emailRule,
                'max:191',
                function (string $attribute, mixed $value, \Closure $fail) {
                    $domain = strtolower((string) substr(strrchr((string) $value, '@') ?: '', 1));
                    if ($domain !== '' && in_array($domain, self::DISPOSABLE_DOMAINS, true)) {
                        $fail('Pagaidu (vienreizējās) e-pasta adreses nav atļautas. Lūdzu, norādiet savu īsto e-pastu.');
                    }
                },
            ],
            'consent' => ['required', 'accepted'],
        ], [
            'email.required' => 'Lūdzu, ievadiet savu e-pasta adresi.',
            'email.email' => 'Lūdzu, ievadiet korektu un eksistējošu e-pasta adresi.',
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
        // 1. Honeypot check (if filled by a bot, fail silently)
        if ($request->filled('website_url')) {
            return $this->respondSuccess($request, 'Paldies! Jūsu e-pasts ir veiksmīgi reģistrēts jaunumu saņemšanai.');
        }

        // 2. Cryptographic Timestamp & Proof-of-Work verification (mandatory in production, or when pow_sig is sent in tests)
        if ($request->filled('pow_sig') || !app()->runningUnitTests()) {
            $powError = $this->verifyProofOfWork($request);
            if ($powError !== null) {
                return $this->respondError($request, $powError, 'captcha');
            }
        }

        // 3. Hardened CAPTCHA verification (supports newsletter_captcha_code or fallback captcha_code)
        $captchaInput = trim((string) $request->input('captcha'));
        $captchaExpected = (string) (session('newsletter_captcha_code') ?: session('captcha_code'));

        if ($captchaInput === '' || $captchaExpected === '' || strcasecmp($captchaInput, $captchaExpected) !== 0) {
            return $this->respondError($request, 'Nepareizs drošības kods! Lūdzu, ievadiet attēlā redzamo kodu vēlreiz.', 'captcha');
        }

        // 4. E-mail RFC/DNS & Disposable domain validation
        $data = $this->validator($request);

        if ($data->fails()) {
            return $this->respondError($request, $data->errors()->first(), 'email');
        }

        // Clear used CAPTCHA codes once validation succeeds so they cannot be replayed
        session()->forget(['newsletter_captcha_code', 'captcha_code']);

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

    /**
     * Verify HMAC-signed challenge timestamp and SHA-256 Proof-of-Work nonce.
     */
    protected function verifyProofOfWork(Request $request): ?string
    {
        $salt = (string) $request->input('pow_salt', '');
        $ts = (int) $request->input('pow_ts', 0);
        $difficulty = (int) $request->input('pow_difficulty', 3);
        $sig = (string) $request->input('pow_sig', '');
        $nonce = (string) $request->input('pow_nonce', '');

        if ($salt === '' || $ts <= 0 || $sig === '' || $nonce === '') {
            return 'Drošības pārbaude neizdevās. Lūdzu, mēģiniet vēlreiz.';
        }

        $expectedPayload = $ts . '|' . $salt . '|' . $difficulty . '|' . $request->ip();
        $expectedSig = hash_hmac('sha256', $expectedPayload, (string) config('app.key', 'credito-secret-key'));

        if (!hash_equals($expectedSig, $sig)) {
            return 'Nederīga drošības sesija. Lūdzu, atsvaidziniet kodu un mēģiniet vēlreiz.';
        }

        $age = time() - $ts;
        // Reject instant bot submissions (< 2 sec) unless in unit tests, and expired tokens (> 30 min)
        if ((!app()->runningUnitTests() && $age < 2) || $age > 1800) {
            return 'Forma tika aizpildīta pārāk ātri vai sesija ir beigusies. Lūdzu, ievadiet jauno drošības kodu.';
        }

        $hash = hash('sha256', $salt . ':' . $nonce);
        $prefix = str_repeat('0', max(1, min(5, $difficulty)));

        if (!str_starts_with($hash, $prefix)) {
            return 'Pārlūkprogrammas drošības pārbaude neizdevās. Lūdzu, mēģiniet vēlreiz.';
        }

        return null;
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
