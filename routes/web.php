<?php

use Illuminate\Support\Facades\Route;

// ===============================
// Controllers
// ===============================
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\PhotographerController;
use App\Http\Controllers\CaseController;
use App\Http\Controllers\PhotographerDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ======================================================================
// PUBLIC & AUTH ROUTES
// ======================================================================

Route::redirect('/', '/login');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registration
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Password Reset
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');


// ======================================================================
// ADMIN ROUTES (AUTHENTICATED)
// ======================================================================

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth'])
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('stations', StationController::class);
        Route::resource('photographers', PhotographerController::class);
        Route::resource('cases', CaseController::class);

        Route::put('/cases/{case}/reassign', [CaseController::class, 'reassign'])
            ->name('cases.reassign');

        // Route::post('/cases/{case}/export-pdf', [CaseController::class, 'exportCasePdf'])
        //     ->name('cases.exportPdf');

        Route::get('cases/{case}/export-pdf', [CaseController::class, 'exportCasePdf'])->name('cases.export-pdf');
        Route::patch('cases/{case}/reassign', [CaseController::class, 'reassign'])->name('cases.reassign');
    });


// ======================================================================
// PHOTOGRAPHER ROUTES (AUTH:PHOTOGRAPHER)
// ======================================================================

// ======================================================================
// PHOTOGRAPHER ROUTES (AUTH:PHOTOGRAPHER)
// ======================================================================
Route::prefix('photographer')
    ->name('photographer.')
    ->middleware(['auth:photographer'])
    ->group(function () {

        Route::get('/dashboard', [CaseController::class, 'photographerIndex'])->name('dashboard');

        Route::prefix('cases/{case}')->name('case.')->group(function () {
            Route::get('/', [CaseController::class, 'photographerShow'])->name('show');

            // The route currently missing:
            Route::put('/update', [CaseController::class, 'photographerUpdate'])->name('update');

            // The route for updating people (Complainant/Accused)
            Route::put('/person/{person}', [CaseController::class, 'updatePerson'])->name('person.update');

            Route::patch('/transition', [CaseController::class, 'transition'])->name('transition');
            Route::post('/upload', [CaseController::class, 'uploadMedia'])->name('upload');
            Route::delete('/media/{media}', [CaseController::class, 'deleteMedia'])->name('media.destroy');
        });
    });
