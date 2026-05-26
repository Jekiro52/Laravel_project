<?php

use App\Http\Controllers\DegreeController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, 'showLoginForm'])->name('home');
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'authenticate'])->name('login.attempt');
Route::redirect('/student-login', '/login');
Route::redirect('/student/login', '/login')->name('student.login');
Route::get('/maintenance', [PagesController::class, 'maintenance'])->name('maintenance');

Route::middleware('groupMiddleware')->group(function () {
    Route::get('/user_profile', [PagesController::class, 'userProfile']);
    Route::get('/user_posts', [PagesController::class, 'userPosts']);
    Route::get('/student_courses', [PagesController::class, 'studentCourses']);
});

Route::middleware('auth.user')->group(function () {
    Route::get('/change-password', [UserController::class, 'showChangePasswordForm'])->name('password.change.form');
    Route::post('/change-password', [UserController::class, 'updatePassword'])->name('password.change.update');
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    Route::middleware('password.changed')->group(function () {
        Route::get('/welcome', [UserController::class, 'welcome'])->name('welcome');

        Route::middleware('role:admin')->group(function () {
            Route::redirect('/admin/dashboard', '/useraccounts')->name('admin.dashboard');
            Route::get('/useraccounts', [UserController::class, 'userAccounts'])->name('useraccounts.index');
            Route::get('/useraccounts/{account}', [UserController::class, 'showUserAccount'])->name('useraccounts.show');
            Route::put('/useraccounts/{account}', [UserController::class, 'updateUserAccount'])->name('useraccounts.update');
            Route::resource('degrees', DegreeController::class);
            Route::resource('students', StudentController::class);
            Route::resource('teachers', TeacherController::class);
        });

        Route::middleware('role:teacher')->group(function () {
            Route::get('/teacher/dashboard', [UserController::class, 'welcome'])->name('teacher.dashboard');
        });
    });

    Route::prefix('student')->name('student.')->group(function () {
        Route::get('/password', [UserController::class, 'showChangePasswordForm'])->name('password.form');
        Route::post('/password', [UserController::class, 'updatePassword'])->name('password.update');

        Route::middleware('password.changed')->group(function () {
            Route::get('/welcome', [UserController::class, 'welcome'])->name('welcome');
        });
    });
});
