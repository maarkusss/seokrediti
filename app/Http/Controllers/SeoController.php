<?php

namespace App\Http\Controllers;

use App\Models\Admincp\Settings;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function manifestJson(): Response
    {
        $manifest_json = Settings::retrieve('manifest_json');
        if (empty($manifest_json)) {
            return abort(404);
        }

        return response($manifest_json, 200, ['Content-Type' => 'application/json']);
    }

    public function robotsTxt(): Response
    {
        $robots_txt = Settings::retrieve('robots_txt');
        if (empty($robots_txt)) {
            if (!app()->isProduction()) {
                return $this->robotsTxtDisallowAll();
            }

            return abort(404);
        }

        return response($robots_txt, 200, ['Content-Type' => 'text/plain']);
    }

    protected function robotsTxtDisallowAll(): Response
    {
        return response(
            "User-agent: *
Disallow: /",
            200,
            [
                'Content-Type' => 'text/plain'
            ]
        );
    }
}
