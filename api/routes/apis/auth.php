<?php
/**
 * Created by PhpStorm.
 * User: meysam.ghanbari
 * Date: 10/4/23
 * Time: 10:29 PM
 */

use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\V1\AuthenticateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::post('/register', [AuthenticateController::class, 'store'])
        ->name('register');

    Route::post('/login', [AuthenticateController::class, 'login'])
        ->name('login');

    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');

    Route::get('/users/{user}/profile', [ProfileController::class, 'info'])
        ->name('user.profile');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return new \App\Http\Resources\UserResource($request->user());
    });

    /*Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
//        ->middleware(['auth', 'signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware(['throttle:6,1'])
        ->name('verification.send');*/

    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile');

    Route::put('/change-avatar', [ProfileController::class, 'updateAvatar'])
        ->name('changeAvatar');

    Route::post('/logout', [AuthenticateController::class, 'destroy'])
        ->name('logout');
});
