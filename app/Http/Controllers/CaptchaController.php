<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CaptchaController extends Controller
{
    /**
     * Generate and return a captcha PNG image.
     */
    public function generate(Request $request): Response
    {
        // Generate random code (avoiding ambiguous chars like 0, O, 1, I, l)
        $characters = '23456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ';
        $length = 6;
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[random_int(0, strlen($characters) - 1)];
        }

        // Store in session
        session(['captcha_code' => $code]);

        // Create image
        $width = 200;
        $height = 70;
        $image = imagecreatetruecolor($width, $height);

        // Background
        $bg = imagecolorallocate($image, 248, 250, 252);
        imagefill($image, 0, 0, $bg);

        // Random background noise dots
        $dotColor = imagecolorallocate($image, 203, 213, 225);
        for ($i = 0; $i < 120; $i++) {
            imagesetpixel($image, random_int(0, $width), random_int(0, $height), $dotColor);
        }

        // Random background lines
        for ($i = 0; $i < 4; $i++) {
            $lineColor = imagecolorallocate($image, random_int(180, 220), random_int(180, 220), random_int(200, 230));
            imageline($image, random_int(0, (int)($width / 3)), random_int(0, $height), random_int((int)($width * 2 / 3), $width), random_int(0, $height), $lineColor);
        }

        // Font path
        $fontPath = resource_path('fonts/captcha.ttf');
        $useTtf = function_exists('imagettftext') && file_exists($fontPath);

        // Palette of pleasant dark text colors
        $textColors = [
            imagecolorallocate($image, 30, 41, 59),
            imagecolorallocate($image, 15, 23, 42),
            imagecolorallocate($image, 30, 58, 138),
            imagecolorallocate($image, 67, 56, 202),
            imagecolorallocate($image, 19, 78, 74),
        ];

        if ($useTtf) {
            $spacing = ($width - 40) / $length;
            for ($i = 0; $i < $length; $i++) {
                $char = $code[$i];
                $color = $textColors[random_int(0, count($textColors) - 1)];
                $fontSize = random_int(20, 24);
                $angle = random_int(-15, 15);
                $x = 18 + ($i * $spacing) + random_int(-2, 2);
                $y = random_int(44, 52);
                imagettftext($image, $fontSize, $angle, (int)$x, $y, $color, $fontPath, $char);
            }
        } else {
            $fontSize = 5;
            $x = 25;
            for ($i = 0; $i < $length; $i++) {
                $char = $code[$i];
                $color = $textColors[random_int(0, count($textColors) - 1)];
                imagestring($image, $fontSize, $x, 26 + random_int(-3, 3), $char, $color);
                $x += 26;
            }
        }

        // Subtle overlay line
        $overlayLine = imagecolorallocate($image, random_int(150, 190), random_int(150, 190), random_int(170, 200));
        imageline($image, 0, random_int(25, 45), $width, random_int(25, 45), $overlayLine);

        // Capture image
        ob_start();
        imagepng($image);
        $imageData = (string)ob_get_clean();
        imagedestroy($image);

        return response($imageData, 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}
