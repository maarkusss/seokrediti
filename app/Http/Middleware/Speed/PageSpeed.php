<?php

namespace App\Http\Middleware\Speed;

use Closure;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

abstract class PageSpeed
{
    /**
     * Apply rules.
     *
     * @param string $buffer
     * @return string
     */
    abstract public function apply($buffer);

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return \Illuminate\Http\Response $response
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (!$this->shouldProcessPageSpeed($request, $response)) {
            return $response;
        }

        $html = (string) $response->getContent();
        $newContent = $this->apply($html);

        return $response->setContent($newContent);
    }

    /**
     * Replace content response.
     *
     * @param  array $replace
     * @param  string $buffer
     * @return string
     */
    protected function replace(array $replace, $buffer)
    {
        return preg_replace(array_keys($replace), array_values($replace), $buffer) ?? $buffer;
    }

    /**
     * Should Process
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Symfony\Component\HttpFoundation\Response $response
     * @return bool
     */
    protected function shouldProcessPageSpeed($request, $response)
    {
        if (!config('page-speed.enable', filter_var(env('LARAVEL_MINIFY', true), FILTER_VALIDATE_BOOLEAN))) {
            return false;
        }

        if ($response instanceof BinaryFileResponse || $response instanceof StreamedResponse) {
            return false;
        }

        $contentType = (string) $response->headers->get('Content-Type', '');
        if ($contentType && !str_contains($contentType, 'text/html') && !str_contains($contentType, 'application/xhtml+xml')) {
            return false;
        }

        $patterns = config('page-speed.skip', []);

        foreach ($patterns as $pattern) {
            if ($request->is($pattern)) {
                return false;
            }
        }

        return true;
    }
}
