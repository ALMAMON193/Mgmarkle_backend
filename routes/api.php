<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Authentication
Route::prefix('auth')->middleware(['auth.rate.limit'])->group(function () {
    Route::post('login', [\App\Http\Controllers\API\Auth\AuthApiController::class, 'loginApi']);
    Route::post('register', [\App\Http\Controllers\API\Auth\AuthApiController::class, 'registerApi']);
    Route::post('verify-email', [\App\Http\Controllers\API\Auth\AuthApiController::class, 'verifyEmailApi']);
    Route::post('forgot-password', [\App\Http\Controllers\API\Auth\AuthApiController::class, 'forgotPasswordApi']);
    Route::post('reset-password', [\App\Http\Controllers\API\Auth\AuthApiController::class, 'resetPasswordApi']);
    Route::post('resend-otp', [\App\Http\Controllers\API\Auth\AuthApiController::class, 'resendOtpApi']);
    Route::post('verify-otp', [\App\Http\Controllers\API\Auth\AuthApiController::class, 'verifyOtpApi']);
});

// Categories & Sub Categories (Public)
Route::get('category-list', [\App\Http\Controllers\API\Category\CategoryApiController::class, 'categoryList']);
Route::get('sub-category-list', [\App\Http\Controllers\API\SubCategory\SubCategoryApiController::class, 'subCategoryList']);

/*
|--------------------------------------------------------------------------
| Protected Routes (Sanctum Authenticated)
|--------------------------------------------------------------------------
*/

Route::middleware(['advanced.throttle', 'auth:sanctum'])->group(function () {

    // --- Common / Shared Routes ---

    // Auth Actions
    Route::post('auth/logout', [\App\Http\Controllers\API\Auth\AuthApiController::class, 'logoutApi']);
    Route::post('auth/update-status', [\App\Http\Controllers\API\Auth\AuthApiController::class, 'updateStatus']);

    // Profile Setup (Initial Setup)
    Route::get('profile', [\App\Http\Controllers\API\ProfileSetup\ProfileSetUpApiController::class, 'show']);
    Route::post('profile-setup', [\App\Http\Controllers\API\ProfileSetup\ProfileSetUpApiController::class, 'store']);

    // Messaging System
    Route::post('/send-message', [\App\Http\Controllers\API\Message\MessageApiController::class, 'sendMessage']);
    Route::get('/messages/{userId}', [\App\Http\Controllers\API\Message\MessageApiController::class, 'getUserMessages']);
    Route::get('/users', [\App\Http\Controllers\API\Message\MessageApiController::class, 'userList']);

    // --- Spiritual Guide Routes ---
    Route::prefix('spiritual-guide')->group(function () {

        // Dashboard / Home
        Route::get('home', [\App\Http\Controllers\API\SpiritualGuide\HomeApiController::class, 'index']);
        Route::post('accept-booking-request/{id}', [\App\Http\Controllers\API\SpiritualGuide\HomeApiController::class, 'acceptBookingRequest']);
        Route::post('decline-booking-request/{id}', [\App\Http\Controllers\API\SpiritualGuide\HomeApiController::class, 'declineBookingRequest']);
        Route::get('booking-requests', [\App\Http\Controllers\API\SpiritualGuide\HomeApiController::class, 'bookingRequests']);
        Route::get('all-schedule', [\App\Http\Controllers\API\SpiritualGuide\HomeApiController::class, 'allSchedule']);

        // Event Management
        Route::get('event-list', [\App\Http\Controllers\API\SpiritualGuide\EventApiController::class, 'eventList']);
        Route::post('event-create', [\App\Http\Controllers\API\SpiritualGuide\EventApiController::class, 'store']);
        Route::get('event-details/{id}', [\App\Http\Controllers\API\SpiritualGuide\EventApiController::class, 'show']);
        Route::post('events/{event}', [\App\Http\Controllers\API\SpiritualGuide\EventApiController::class, 'update']);
        Route::delete('event-delete/{id}', [\App\Http\Controllers\API\SpiritualGuide\EventApiController::class, 'eventDelete']);

        // Profile Management & Availability
        Route::prefix('profile')->group(function () {
            Route::get('details', [\App\Http\Controllers\API\SpiritualGuide\Profile\ProfileApiController::class, 'profileDetails']);
            Route::post('picture-update', [\App\Http\Controllers\API\SpiritualGuide\Profile\ProfileApiController::class, 'updateProfilePicture']);
            // Availability / Slots
            Route::get('available-slot', [\App\Http\Controllers\API\SpiritualGuide\Profile\ProfileApiController::class, 'availableSlots']);
            Route::post('add-slot', [\App\Http\Controllers\API\SpiritualGuide\Profile\ProfileApiController::class, 'addSlot']);
        });
        // Zoom Integration
        Route::post('/zoom/generate-sdk-token', [\App\Http\Controllers\API\Zoom\ZoomController::class, 'generateSdkToken']);
        Route::get('/zoom/sdk-credentials', [\App\Http\Controllers\API\Zoom\ZoomController::class, 'getSdkCredentials']);
    });
    // --- Seeker Routes ---
    Route::prefix('seeker')->group(function () {

        // profie details and profie update
        Route::prefix('profile')->group(function () {
            Route::get('details', [\App\Http\Controllers\API\Seeker\Profile\ProfileApiController::class, 'profileDetails']);
            Route::post('update', [\App\Http\Controllers\API\Seeker\Profile\ProfileApiController::class, 'updateProfile']);
        });
        // Dashboard / Home
        Route::get('home', [\App\Http\Controllers\API\Seeker\HomeController::class, 'index']);
        // Profile Search & Filtering
        Route::get('profiles/search', [\App\Http\Controllers\API\Seeker\SearchApiController::class, 'searchProfiles']);
        Route::get('profiles/filter', [\App\Http\Controllers\API\Seeker\SearchApiController::class, 'filterProfiles']);
        // Leader Details
        Route::get('spiritual-guides/{id}', [\App\Http\Controllers\API\Seeker\LeaderController::class, 'show']);
        // Event Interaction
        Route::get('events/{id}', [\App\Http\Controllers\API\Seeker\EventController::class, 'show']);
        Route::post('join-event/{id}', [\App\Http\Controllers\API\Seeker\EventController::class, 'joinEvent']);
        Route::post('ratting', [\App\Http\Controllers\API\Seeker\RatingController::class, 'store']);
        Route::get('my-event-bookings', [\App\Http\Controllers\API\Seeker\EventController::class, 'myBookings']);

        // Payment
        // Route::post('payment/setup-intent', [\App\Http\Controllers\API\Seeker\PaymentCardController::class, 'createSetupIntent']);
        Route::get('payment/methods', [\App\Http\Controllers\API\Seeker\PaymentCardController::class, 'getPaymentMethods']);
        Route::post('payment/add', [\App\Http\Controllers\API\Seeker\PaymentCardController::class, 'addPaymentMethod']);
        Route::post('payment/remove', [\App\Http\Controllers\API\Seeker\PaymentCardController::class, 'detachPaymentMethod']);
        Route::post('payment/default', [\App\Http\Controllers\API\Seeker\PaymentCardController::class, 'setDefaultPaymentMethod']);

        // New Payment Routes
        Route::post('checkout-session', [\App\Http\Controllers\API\Seeker\PaymentController::class, 'checkoutSession']);

        // Appointment Routes
        Route::get('appointments', [\App\Http\Controllers\API\Seeker\AppointmentController::class, 'index']);
        Route::get('appointments/{id}', [\App\Http\Controllers\API\Seeker\AppointmentController::class, 'show']);
        Route::post('appointments/{id}/zoom', [\App\Http\Controllers\API\Seeker\AppointmentController::class, 'updateZoom']);
    });

    // Public Webhook Route
    Route::post('stripe/webhook', [\App\Http\Controllers\API\Seeker\PaymentController::class, 'handleWebhook']);

});
