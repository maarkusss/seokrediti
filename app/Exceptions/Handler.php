<?php

namespace App\Exceptions;

use App\Models\Admincp\Settings;
use Throwable;
use GuzzleHttp\Client;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
     * @param  \Exception  $exception
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
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */

    public function render($request, Throwable $exception)
    {
        return parent::render($request, $exception);
    }

    protected function exportToMonitoring(Throwable $e)
    {
        try {
            $client = new Client([
                'verify' => false,
                'connect_timeout' => 3,
                'timeout' => 5,
            ]);

            $client->post('https://www.monitoring.goodday.group/api/errors/cp', [
                'headers' => [
                    'apikey' => Settings::retrieve('api_key'),
                    'Accept' => 'application/json',
                ],
                'json' => [
                    'error' => $e->getMessage(),
                    'status_code' => $e->getCode() > 200 && $e->getCode() < 600 ? $e->getCode() : 500,
                    'site' => config('app.name'),
                    'site_url' => config('app.url'),
                ]
            ]);
        } catch (Throwable $monitoring_exception) {
        }
    }
}
