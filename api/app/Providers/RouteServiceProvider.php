<?php

namespace App\Providers;

use App\Models\SchoolPrinciple;
use App\Models\SchoolStudent;
use App\Models\SchoolTeacher;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /** @var string $apiNamespace */
    protected $apiNamespace ='App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            $latest_api_version = config('app.api_latest');

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // Latest API Version
            Route::prefix('api/latest')
                ->middleware(['api', "api.version:v{$latest_api_version}"])
                ->namespace("{$this->apiNamespace}\V{$latest_api_version}")
                ->group(base_path("routes/api_v{$latest_api_version}.php"));

            // API Version 1
            Route::prefix('api/v1')
                ->middleware(['api', 'api.version:v1'])
                ->namespace("{$this->apiNamespace}\V1")
                ->group(base_path('routes/api_v1.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });

        // Will use to find SchoolPrinciple by user id
        Route::bind('userPrinciple', function ($value) {
            return User::where('id', $value)->principles()->first();
        });

        // Will use to find SchoolTeacher by user id
        Route::bind('userTeacher', function ($value) {
            return User::where('id', $value)->teachers()->first();
        });

        // Will use to find SchoolStudent by user id
        Route::bind('userStudent', function ($value) {
            return User::where('id', $value)->students()->first();
        });
    }
}
