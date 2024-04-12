<?php
/**
 * Created by PhpStorm.
 * User: meysam.ghanbari
 * Date: 10/9/23
 * Time: 8:08 PM
 */

use App\Http\Controllers\V1\SchoolPrincipleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\SchoolController;

Route::middleware('auth:sanctum,can:admin')->group(function () {
    // Assign the principle by user id to the given school id.
    Route::post('principles/{user}/assign-school/{school}', [SchoolPrincipleController::class, 'assignSchool'])
        ->name('principle.assignSchool');

    // Show principle data base on given user id not principle id.
    Route::get('principles/{userPrinciple}', [SchoolPrincipleController::class, 'show'])
        ->name('principle.show');

    Route::apiResources([
        'schools' => SchoolController::class,
        'principles' => SchoolPrincipleController::class,
    ]);

    Route::prefix('schools/{school}')->group(function () {
        Route::apiResources([
            'classrooms' => SchoolController::class,
        ]);
    });
});

Route::middleware('auth:sanctum,can:principle')->group(function () {
    Route::apiResources([
        'teachers' => SchoolController::class,
        'students' => SchoolController::class,
    ]);

    Route::prefix('schools/{school}/classrooms/{classroom}')->group(function () {
//        Route::post('add-teacher');
//        Route::post('add-student');
    });
});