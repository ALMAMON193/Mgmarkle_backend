<?php

use App\Http\Controllers\API\Auth\AuthApiController;
use App\Http\Controllers\API\Category\CategoryApiController;
use App\Http\Controllers\API\ProfileSetup\ProfileSetUpApiController;
use App\Http\Controllers\API\Seeker\HomeController;
use App\Http\Controllers\API\Seeker\RatingController;
use App\Http\Controllers\API\SpiritualGuide\EventApiController;
use App\Http\Controllers\API\SpiritualGuide\HomeApiController;
use App\Http\Controllers\API\SpiritualGuide\Profile\ProfileApiController;
use App\Http\Controllers\API\SubCategory\SubCategoryApiController;
use Illuminate\Support\Facades\Route;

// Public authentication routes
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthApiController::class, 'loginApi']); // User login
    Route::post('register', [AuthApiController::class, 'registerApi']); // User registration
    Route::post('verify-email', [AuthApiController::class, 'verifyEmailApi']); // Verify email
    Route::post('forgot-password', [AuthApiController::class, 'forgotPasswordApi']); // Forgot password
    Route::post('reset-password', [AuthApiController::class, 'resetPasswordApi']); // Reset password
    Route::post('resend-otp', [AuthApiController::class, 'resendOtpApi']); // Resend OTP
    Route::post('verify-otp', [AuthApiController::class, 'verifyOtpApi']); // Verify OTP
});
Route::middleware('auth:sanctum')->group(function () {
    Route::post('auth/logout', [AuthApiController::class, 'logoutApi']);

    // profile setup
    Route::get('profile', [ProfileSetUpApiController::class, 'show']);
    Route::post('profile-setup', [ProfileSetUpApiController::class, 'store']);

    //   Spiritual Guide routes
    Route::prefix('spiritual-guide')->group(function () {
        // Event routes
        Route::get('event-list', [EventApiController::class, 'eventList']);
        Route::post('event-create', [EventApiController::class, 'store']);
        Route::post('events/{event}', [EventApiController::class, 'update']);
        Route::get('event-details/{id}', [EventApiController::class, 'show']);
        Route::delete('event-delete/{id}', [EventApiController::class, 'eventDelete']);

        // home api routes
        Route::get('home', [HomeApiController::class, 'index']);

        Route::prefix('profile')->group(function () {
            // Event routes
            Route::get('available-slot', [ProfileApiController::class, 'availableSlots']);
            Route::get('details', [ProfileApiController::class, 'profileDetails']);
            Route::post('picture-update', [ProfileApiController::class, 'updateProfilePicture']);
        });
    });
    //   Spiritual Guide routes
    Route::prefix('seeker')->group(function () {
        // home
        Route::get('home', [HomeController::class, 'index']);
        // Event routes
        Route::get('ratting', [RatingController::class, 'store']);

    });
});
// category and Sub category
Route::get('category-list', [CategoryApiController::class, 'categoryList']);
Route::get('sub-category-list', [SubCategoryApiController::class, 'subCategoryList']);
