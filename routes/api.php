<?php

use App\Http\Controllers\API\Auth\AuthApiController;
use App\Livewire\Dashboard\Overview;
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

});

Route::get('dashboard', Overview::class)->name('dashboard');
