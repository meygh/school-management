<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class InputNameNormalizer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $inputs = $request->all();

        foreach ($inputs as $key => $value) {
            $new_key = Str::snake($key);

            if ($key !== $new_key) {
                unset($inputs[$key]);
                $inputs[$new_key] = $value;
            }
        }

        $request->replace($inputs);

        return $next($request);
    }
}
