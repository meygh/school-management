<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/greeting/{locale}', function (string $locale) {
    if (!in_array($locale, ['en', 'fa'])) {
        abort(400);
    }

    app()::setLocale($locale);
});

require __DIR__ . '/api_v1.php';
