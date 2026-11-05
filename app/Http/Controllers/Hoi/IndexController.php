<?php

namespace App\Http\Controllers\Hoi;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function callApiFromHoi()
    {
        $fallbackDomain = parse_url((string) config('app.url'), PHP_URL_HOST) ?: 'localhost';
        $domain = env('SENDER_DOMAIN') ?: $fallbackDomain;

        if (request()->email && request()->htmllink && request()->redirectlink && request()->subject) {
            $content = file_get_contents(request()->htmllink);
            if ($content) {
                $mail_text = str_replace(['{link}', '{email}'], [request()->redirectlink, request()->email], $content);
                $this->sendHoiEmail(
                    request()->email,
                    '=?UTF-8?B?' . base64_encode(stripslashes(urldecode(request()->subject))) . '?=',
                    $mail_text,
                    brand_name() . ' <info@' . $domain . '>',
                    $domain
                );
                $data['status'] = 'done';
            } else {
                $data['status'] = 'error';
            }
        } else {
            $data['status'] = 'error';
        }

        return json_encode($data);
    }

    public function sendHoiEmail($email, $subject, $message, $from, $domain)
    {
        $boundary = md5(uniqid() . microtime());

        $headers = 'From: ' . $from . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/alternative; boundary=\"$boundary\"\r\n\r\n";

        $body = "--$boundary\r\n" .
            "Content-Type: text/plain; charset=UTF-8\r\n" .
            "Content-Transfer-Encoding: base64\r\n\r\n";
        $body .= chunk_split(base64_encode(strip_tags($message)));

        $body .= "--$boundary\r\n" .
            "Content-Type: text/html; charset=UTF-8\r\n" .
            "Content-Transfer-Encoding: base64\r\n\r\n";
        $body .= chunk_split(base64_encode($message));
        $body .= "--$boundary--";

        if (is_array($email)) {
            foreach ($email as $e) {
                mail($e, $subject, $body, $headers, '-f info@' . $domain);
            }
        } else {
            mail($email, $subject, $body, $headers, '-f info@' . $domain);
        }
    }
}
