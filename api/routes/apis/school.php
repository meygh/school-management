<?php
/**
 * Created by PhpStorm.
 * User: meysam.ghanbari
 * Date: 10/9/23
 * Time: 8:08 PM
 */

use App\Http\Controllers\V1\SchoolClassroomController;
use App\Http\Controllers\V1\SchoolPrincipleController;
use App\Http\Controllers\V1\SchoolStudentController;
use App\Http\Controllers\V1\SchoolTeacherController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\SchoolController;

Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('can:admin')->group(function () {
        // Assign the principle by user id to the given school id.
        Route::post('principles/{user}/assign-school/{school}', [SchoolPrincipleController::class, 'assignSchool'])
            ->name('principle.assignSchool');

        // Show principle data base on given user id not principle id.
        Route::get('principles/user/{userPrinciple}', [SchoolPrincipleController::class, 'showByUserId'])
            ->name('principle.showByUser');

        Route::apiResources([
            'schools' => SchoolController::class,
            'principles' => SchoolPrincipleController::class,
        ]);

        Route::prefix('schools/{school}')->group(function () {
            Route::apiResources([
                'classrooms' => SchoolClassroomController::class,
            ]);
        });
    });

    Route::middleware('can:principle')->group(function () {
        /** Teachers CRUD */
        // Assign the teachr by user id to the given school id.
        Route::post('teachers/{userTeacher}/assign-classroom/{classroom}', [SchoolTeacherController::class, 'assignClassroom'])
            ->name('teachers.assignClassroom');

        // Show teacher data based on given user id not teacher id.
        Route::get('teachers/user/{userTeacher}', [SchoolTeacherController::class, 'showByUserId'])
            ->name('teachers.showByUser');

        Route::delete('teachers/{userTeacher}', [SchoolTeacherController::class, 'destroy'])
            ->name('teachers.destroy');

        /** Students CRUD */
        // Assign the student by user id to the given school id.
        Route::post('students/{userStudent}/assign-classroom/{classroom}', [SchoolStudentController::class, 'assignClassroom'])
            ->name('teachers.assignClassroom');

        // Show teacher data based on given user id not teacher id.
        Route::get('students/user/{userStudent}', [SchoolStudentController::class, 'showByUserId'])
            ->name('students.showByUser');

        Route::delete('students/{userStudent}', [SchoolStudentController::class, 'destroy'])
            ->name('students.destroy');

        /** Resources CRUD */
        Route::apiResources([
            'teachers' => SchoolTeacherController::class,
            'students' => SchoolStudentController::class,
        ]);
    });

    /** School classroom based Routes */
    Route::get('schools/{school}/classrooms', [SchoolClassroomController::class, 'index'])
        ->name('classroom.list');

    /** School classroom students list */
    Route::get('classrooms/{classroom}/students', [SchoolClassroomController::class, 'listOfStudents'])
        ->name('classroom.students.list');
});
