<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;

class ProfileJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Check if debugbar is enabled
        if(!app()->bound('debugbar') || !app('debugbar')->isEnabled()) {
            return $response;
        }

        // Profile the Json response
        if ($response instanceof JsonResponse && $request->has('_debug')) {
            $response->setData(array_merge($response->getData(true), [
                'debugbar' => app('debugbar')->getData(true)
            ]));
        }

        return $response;
    }
}
