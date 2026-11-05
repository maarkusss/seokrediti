<?php

namespace App\Exceptions;

use App\Models\Admincp\Settings;
use GuzzleHttp\Client;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        if ($this->shouldReport($exception) && app()->isProduction()) {
            $this->exportToMonitoring($exception);
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        return parent::render($request, $exception);
    }

    protected function exportToMonitoring(Throwable $e)
    {
        $webhookUrl = env('MONITORING_WEBHOOK_URL');
        if (empty($webhookUrl)) {
            return;
        }

        try {
            $client = new Client([
                'connect_timeout' => 3,
                'timeout' => 5,
            ]);

            $client->post($webhookUrl, [
                'headers' => [
                    'apikey' => Settings::retrieve('api_key'),
                    'Accept' => 'application/json',
                ],
                'json' => [
                    'error' => $e->getMessage(),
                    'status_code' => $e->getCode() > 200 && $e->getCode() < 600 ? $e->getCode() : 500,
                    'site' => brand_name(),
                    'site_url' => config('app.url'),
                ],
            ]);
        } catch (Throwable $monitoring_exception) {
        }
    }
}
