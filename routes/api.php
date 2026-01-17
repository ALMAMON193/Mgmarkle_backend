<?php

use App\Http\Controllers\API\Auth\AuthApiController;
use App\Http\Controllers\API\Category\CategoryApiController;
use App\Http\Controllers\API\Message\MessageApiController;
use App\Http\Controllers\API\ProfileSetup\ProfileSetUpApiController;
use App\Http\Controllers\API\Seeker\HomeController;
use App\Http\Controllers\API\Seeker\RatingController;
use App\Http\Controllers\API\Seeker\SearchApiController;
use App\Http\Controllers\API\SpiritualGuide\EventApiController;
use App\Http\Controllers\API\SpiritualGuide\HomeApiController;
use App\Http\Controllers\API\SpiritualGuide\Profile\ProfileApiController;
use App\Http\Controllers\API\SubCategory\SubCategoryApiController;
use App\Http\Controllers\API\Zoom\ZoomController;
use Illuminate\Support\Facades\Route;

// Public authentication routes
Route::prefix('auth')->middleware(['auth.rate.limit'])->group(function () {
    Route::post('login', [AuthApiController::class, 'loginApi']);
    Route::post('register', [AuthApiController::class, 'registerApi']);
    Route::post('verify-email', [AuthApiController::class, 'verifyEmailApi']);
    Route::post('forgot-password', [AuthApiController::class, 'forgotPasswordApi']);
    Route::post('reset-password', [AuthApiController::class, 'resetPasswordApi']);
    Route::post('resend-otp', [AuthApiController::class, 'resendOtpApi']);
    Route::post('verify-otp', [AuthApiController::class, 'verifyOtpApi']);
});
Route::middleware(['advanced.throttle', 'auth:sanctum'])->group(function () {
    // Send message
    Route::post('/send-message', [MessageApiController::class, 'sendMessage']);

    // Conversation between two users
    Route::get('/messages/{userId}', [MessageApiController::class, 'getUserMessages']);
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
            // add slot
            Route::post('add-slot', [ProfileApiController::class, 'addSlot']);
            Route::get('details', [ProfileApiController::class, 'profileDetails']);
            Route::post('picture-update', [ProfileApiController::class, 'updateProfilePicture']);
        });

        Route::middleware('auth:sanctum')->group(function () {
            // Zoom Routes
            Route::post('/zoom/meeting', [ZoomController::class, 'create']);
            Route::get('/zoom/meetings', [ZoomController::class, 'list']);
            Route::delete('/zoom/meeting/{id}', [ZoomController::class, 'delete']);
        });
    });
    //   Spiritual Guide routes
    Route::prefix('seeker')->group(function () {
        // home
        Route::get('home', [HomeController::class, 'index']);
        // Event routes
        Route::post('ratting', [RatingController::class, 'store']);

        // search profile
        Route::get('profiles/search', [SearchApiController::class, 'searchProfiles']);
        Route::get('profiles/filter', [SearchApiController::class, 'filterProfiles']);
    });
});
// category and Sub category
Route::get('category-list', [CategoryApiController::class, 'categoryList']);
Route::get('sub-category-list', [SubCategoryApiController::class, 'subCategoryList']);
// User list
Route::get('/users', [MessageApiController::class, 'userList']);
