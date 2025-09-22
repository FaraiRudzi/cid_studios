<?php

use Illuminate\Support\Facades\Route;

// --- Controller Imports ---
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\PhotographerController;
use App\Http\Controllers\CaseController;
use App\Http\Controllers\PhotographerDashboardController;
use App\Http\Controllers\PhotographerMediaController;
use App\Http\Controllers\PhotographerCaseController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| This file defines all the web routes for your application.
*/

// =========================================================================
// SECTION 1: PUBLIC & AUTHENTICATION ROUTES
// =========================================================================

// Redirect the root URL to the main unified login page.
Route::get('/', fn() => redirect()->route('login'));

// All standard authentication routes are handled by these definitions.
// Since the LoginController is unified, there is no need for separate photographer login routes.
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registration Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Password Reset Routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');


// =========================================================================
// SECTION 2: ADMIN PROTECTED ROUTES
// =========================================================================
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('stations', StationController::class);
    Route::resource('photographers', PhotographerController::class);
    Route::resource('cases', CaseController::class);
    Route::put('/cases/{case}/reassign', [CaseController::class, 'reassign'])->name('cases.reassign');
    Route::post('/cases/{case}/export-pdf', [CaseController::class, 'exportCasePdf'])->name('cases.exportPdf');
    

});


// =========================================================================
// SECTION 3: PHOTOGRAPHER PROTECTED ROUTES
// =========================================================================

// In routes/web.php

// 4. PHOTOGRAPHER PROTECTED ROUTES
// =========================================================================
Route::prefix('photographer')->name('photographer.')->middleware('auth:photographer')->group(function () {

    // Main dashboard for the photographer
    Route::get('/dashboard', [PhotographerDashboardController::class, 'index'])->name('dashboard');

    // Group for all actions related to a specific case
    Route::prefix('cases/{case}')->name('case.')->group(function() {
        // --- THIS IS THE EXISTING ROUTE ---
        // Handles GET requests to view the case workspace
        Route::get('/', [PhotographerDashboardController::class, 'show'])->name('show');

        // --- THIS IS THE NEW, CRITICAL ROUTE ---
        // Handles PUT requests from the main form to update case details
        Route::put('/update', [PhotographerDashboardController::class, 'update'])->name('update');
        Route::put('/person/{person}', [PhotographerDashboardController::class, 'updatePerson'])->name('person.update');

        // --- ADD THESE ROUTES FOR YOUR OTHER FORMS ---
        // Handles PUT requests from the "Finalize" form
        Route::put('/finalize', [PhotographerDashboardController::class, 'finalize'])->name('finalize');
        
        // Handles POST requests from the "Upload Media" form
        Route::post('/media', [PhotographerMediaController::class, 'store'])->name('media.store');
        
    });

    // Group for actions on a specific media file
    Route::prefix('media/{media}')->name('media.')->group(function() {
        // Handles DELETE requests from the "Delete" button in the gallery
        Route::delete('/destroy', [PhotographerDashboardController::class, 'destroyMedia'])->name('destroy');
        // You can add routes for updating media descriptions here later
        // Route::put('/update', [PhotographerMediaController::class, 'update'])->name('update');
    });

}); // End of photographer middleware group
