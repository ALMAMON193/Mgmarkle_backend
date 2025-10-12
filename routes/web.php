<?php

use App\Livewire\Dashboard\Category\Index as CategoryIndex;
use App\Livewire\Dashboard\SubCategory\Index as SubCategoryIndex;

Route::prefix('admin')->group(function () {

    // Category Routes
    Route::prefix('category')->group(function () {
        Route::get('/', CategoryIndex::class)->name('category.index');

    });

    // SubCategory Routes
    Route::prefix('sub-category')->group(function () {
        Route::get('/', SubCategoryIndex::class)->name('sub-category.index');

    });

});

require __DIR__.'/auth.php';
require __DIR__.'/api.php';
