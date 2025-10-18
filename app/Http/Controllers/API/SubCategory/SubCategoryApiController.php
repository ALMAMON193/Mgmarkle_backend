<?php

namespace App\Http\Controllers\API\SubCategory;

use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Cache;

class SubCategoryApiController extends Controller
{
    use ApiResponse;

    public function subCategoryList()
    {
        $cacheKey = 'sub_categories_all';

        $categories = Cache::remember($cacheKey, 60 * 60, function () {
            return SubCategory::latest()->get();
        });

        return $this->sendResponse($categories, __('Sub Categories Fetched Successfully'));
    }
}
