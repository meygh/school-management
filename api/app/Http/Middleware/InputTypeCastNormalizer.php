<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;

class InputTypeCastNormalizer
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        $input = $request->all();
        array_walk_recursive($input, function(&$input) {
            if ($input === 'null') {
                $input = null;
            } else if ($input === 'false') {
                $input = false;
            } else if ($input === 'true') {
                $input = true;
            }
        });
        $request->merge($input);

        return $next($request);

    }
}
