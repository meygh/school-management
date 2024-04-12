<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class APIVersion
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     * @param string $version of the called version of the api
     *
     * @return Response
     */
    public function handle(Request $request, Closure $next, $version): Response
    {
        config([
            'app.api.version' => $version
        ]);
        
        return $next($request);
    }
}
