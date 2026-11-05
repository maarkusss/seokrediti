<?php

namespace App\Http\Middleware;

use App\Models\Admincp\Settings;
use Closure;
use Symfony\Component\HttpFoundation\Response;

class HasApiKey
{
    /**
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->hasHeader('apikey')) {
            return response()->json([
                'message' => 'Not Found',
            ], Response::HTTP_NOT_FOUND);
        }

        if (
            false === Settings::query()
            ->where('name', 'api_key')
            ->where('value', $request->header('apikey'))
            ->exists()
        ) {
            return response()->json([
                'message' => 'Forbidden',
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
