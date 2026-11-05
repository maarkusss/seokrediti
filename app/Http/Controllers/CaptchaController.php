<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class CaptchaController extends Controller
{
    /**
     * Generate and return a captcha PNG image.
     * Supports ?scope=newsletter (hardened 6-char distorted captcha) or default session key.
     */
    public function generate(Request $request): Response
    {
        $scope = $request->query('scope') === 'newsletter' ? 'newsletter' : 'default';

        // Generate random code (avoiding ambiguous chars like 0, O, 1, I, l)
        $characters = '23456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ';
        $length = 6;
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[random_int(0, strlen($characters) - 1)];
        }

        // Store in session (both scoped and default so existing forms continue working)
        if ($scope === 'newsletter') {
            session([
                'newsletter_captcha_code' => $code,
                'captcha_code' => $code,
            ]);
        } else {
            session(['captcha_code' => $code]);
        }

        $width = 220;
        $height = 72;

        // 1. Render base canvas with characters
        $base = imagecreatetruecolor($width, $height);
        $bgR = 246;
        $bgG = 243;
        $bgB = 236; // Warm newsprint #F6F3EC
        $bg = imagecolorallocate($base, $bgR, $bgG, $bgB);
        imagefill($base, 0, 0, $bg);

        // Subtle cross-hatch security grid
        $gridColor = imagecolorallocate($base, 224, 218, 205);
        for ($gx = 0; $gx < $width; $gx += random_int(14, 20)) {
            imageline($base, $gx, 0, $gx + random_int(-6, 6), $height, $gridColor);
        }
        for ($gy = 0; $gy < $height; $gy += random_int(14, 18)) {
            imageline($base, 0, $gy, $width, $gy + random_int(-4, 4), $gridColor);
        }

        // Font path
        $fontPath = resource_path('fonts/captcha.ttf');
        $useTtf = function_exists('imagettftext') && file_exists($fontPath);

        // High-contrast editorial ink & claret palette
        $textColors = [
            imagecolorallocate($base, 21, 20, 18),   // Ink #151412
            imagecolorallocate($base, 158, 27, 27),  // Claret #9E1B1B
            imagecolorallocate($base, 38, 36, 33),   // Charcoal
            imagecolorallocate($base, 120, 20, 20),  // Deep burgundy
            imagecolorallocate($base, 25, 42, 68),   // Slate navy
        ];

        // Faint decoy ghost characters in the background to confuse OCR segmentation
        if ($useTtf) {
            $ghostColor = imagecolorallocate($base, 214, 208, 195);
            for ($g = 0; $g < 5; $g++) {
                $ghostChar = $characters[random_int(0, strlen($characters) - 1)];
                imagettftext(
                    $base,
                    random_int(14, 18),
                    random_int(-35, 35),
                    random_int(10, $width - 25),
                    random_int(20, $height - 10),
                    $ghostColor,
                    $fontPath,
                    $ghostChar
                );
            }
        }

        // Draw primary security characters
        if ($useTtf) {
            $spacing = ($width - 36) / $length;
            for ($i = 0; $i < $length; $i++) {
                $char = $code[$i];
                $color = $textColors[random_int(0, count($textColors) - 1)];
                $fontSize = random_int(21, 25);
                $angle = random_int(-22, 22);
                $x = 16 + ($i * $spacing) + random_int(-2, 2);
                $y = random_int(44, 54);
                imagettftext($base, $fontSize, $angle, (int) $x, $y, $color, $fontPath, $char);
            }
        } else {
            $fontSize = 5;
            $x = 25;
            for ($i = 0; $i < $length; $i++) {
                $char = $code[$i];
                $color = $textColors[random_int(0, count($textColors) - 1)];
                imagestring($base, $fontSize, $x, 26 + random_int(-4, 4), $char, $color);
                $x += 28;
            }
        }

        // 2. Apply 2D sinusoidal wave warp (defeats standard OCR character slicing)
        $warped = imagecreatetruecolor($width, $height);
        $warpedBg = imagecolorallocate($warped, $bgR, $bgG, $bgB);
        imagefill($warped, 0, 0, $warpedBg);

        $periodX = random_int(110, 150) / 10.0;
        $periodY = random_int(90, 130) / 10.0;
        $phaseX = random_int(0, 628) / 100.0;
        $phaseY = random_int(0, 628) / 100.0;
        $ampX = random_int(32, 46) / 10.0;
        $ampY = random_int(25, 38) / 10.0;

        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $sx = (int) round($x + sin($y / $periodX + $phaseX) * $ampX);
                $sy = (int) round($y + cos($x / $periodY + $phaseY) * $ampY);

                if ($sx >= 0 && $sx < $width && $sy >= 0 && $sy < $height) {
                    $rgb = imagecolorat($base, $sx, $sy);
                    imagesetpixel($warped, $x, $y, $rgb);
                }
            }
        }
        imagedestroy($base);

        // 3. Draw curved sine interference wave & noise pepper over warped text
        for ($w = 0; $w < 2; $w++) {
            $waveColor = $textColors[random_int(0, count($textColors) - 1)];
            $wPhase = random_int(0, 628) / 100.0;
            $wAmp = random_int(8, 15);
            $wMid = random_int(24, 48);
            $wFreq = random_int(22, 34);

            for ($x = 0; $x < $width; $x++) {
                $wy = (int) round($wMid + sin($x / $wFreq + $wPhase) * $wAmp);
                if ($wy >= 1 && $wy < $height - 1) {
                    imagesetpixel($warped, $x, $wy, $waveColor);
                    imagesetpixel($warped, $x, $wy + 1, $waveColor);
                }
            }
        }

        // Foreground noise dots
        $dotDark = imagecolorallocate($warped, 140, 132, 120);
        for ($i = 0; $i < 160; $i++) {
            imagesetpixel($warped, random_int(0, $width - 1), random_int(0, $height - 1), $dotDark);
        }

        // Capture PNG
        ob_start();
        imagepng($warped);
        $imageData = (string) ob_get_clean();
        imagedestroy($warped);

        return response($imageData, 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    /**
     * Issue a signed timestamp + Proof-of-Work challenge for the newsletter modal.
     */
    public function challenge(Request $request): JsonResponse
    {
        $issuedAt = time();
        $salt = Str::random(16);
        $difficulty = 3; // Require SHA-256 hex digest starting with "000" (~4096 hashes in browser JS, ~15ms)

        $payload = $issuedAt . '|' . $salt . '|' . $difficulty . '|' . $request->ip();
        $signature = hash_hmac('sha256', $payload, (string) config('app.key', 'credito-secret-key'));

        return response()->json([
            'salt' => $salt,
            'ts' => $issuedAt,
            'difficulty' => $difficulty,
            'sig' => $signature,
        ], 200, [
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);
    }
}
