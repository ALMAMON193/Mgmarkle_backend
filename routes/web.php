<?php

use App\Livewire\Auth\LoginComponent;
use App\Livewire\Dashboard\Category\Index as CategoryIndex;
use App\Livewire\Dashboard\Overview;
use App\Livewire\Dashboard\SubCategory\Index as SubCategoryIndex;

Route::get('/', LoginComponent::class)->name('login')->middleware('auth.rate.limit');
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
})->name('logout')->middleware(['auth.rate.limit']);
Route::get('dashboard', Overview::class)->name('dashboard')->middleware('auth');
Route::prefix('admin')->middleware('auth')->group(function () {
    // Category Routes
    Route::prefix('category')->group(function () {
        Route::get('/', CategoryIndex::class)->name('category.index');
    });
    // SubCategory Routes
    Route::prefix('sub-category')->group(function () {
        Route::get('/', SubCategoryIndex::class)->name('sub-category.index');

    });
});
require __DIR__.'/api.php';
