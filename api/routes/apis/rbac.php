<?php
/**
 * Created by PhpStorm.
 * User: meysam.ghanbari
 * Date: 10/9/23
 * Time: 8:08 PM
 */

use App\Http\Controllers\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum,can:admin')->group(function () {
    Route::apiResources([
        'users' => UserController::class,
    ]);

    Route::get('users/find/{term}', [UserController::class, 'find']);
    Route::get('users/{field}', [UserController::class, 'getSetFilterValues']);
});
