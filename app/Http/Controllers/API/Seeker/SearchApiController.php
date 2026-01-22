<?php

namespace App\Http\Controllers\API\Seeker;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class SearchApiController extends Controller
{
    use ApiResponse;

    public function searchProfiles(Request $request)
    {
        // Search by User Name, Category, or Topic
        $query = Profile::query()->with(['user', 'category', 'subCategories']);

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Specific field filters if provided separately
        if ($request->filled('user_name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->user_name.'%');
            });
        }

        $profiles = $query->get()->map(function ($profile) {
            return [
                'user_id' => $profile->user_id,
                'name' => $profile->user->name ?? null,
                'category' => $profile->category->name ?? null,
                'sub_categories' => $profile->subCategories->pluck('name'), // Return list of subcategories
                'avatar' => $profile->profile_picture ? Helper::generateURL($profile->profile_picture) : null,
                'average_rating' => $profile->user->averageRating(), // Use model helper
            ];
        });

        return $this->sendResponse($profiles, __('Profiles Retrieved Successfully'));
    }

    public function filterProfiles(Request $request)
    {
        // Base query with relationships
        $query = Profile::with(['user', 'category', 'subCategories']);

        // Filter by Category Name (Exact or Partial)
        if ($request->filled('category_name')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->category_name.'%');
            });
        }

        // Filter by Sub Category Name
        if ($request->filled('sub_category_name')) {
            $subCategoryName = urldecode($request->sub_category_name);
            $query->whereHas('subCategories', function ($q) use ($subCategoryName) {
                $q->where('name', 'like', '%'.$subCategoryName.'%');
            });
        }
        $profiles = $query->get();
        // Filter by Rating (Calculated field)
        if ($request->filled('rating')) {
            $minRating = (float) $request->rating;
            $profiles = $profiles->filter(function ($profile) use ($minRating) {
                return $profile->user->averageRating() >= $minRating;
            });
        }

        // Map results
        $data = $profiles->values()->map(function ($profile) { // values() resets keys after filter
            return [
                'user_id' => $profile->user_id,
                'name' => $profile->user->name ?? null,
                'category' => $profile->category->name ?? null,
                'sub_categories' => $profile->subCategories->pluck('name'),
                'avatar' => $profile->profile_picture ? Helper::generateURL($profile->profile_picture) : null,
                'average_rating' => $profile->user->averageRating(),
            ];
        });

        return $this->sendResponse($data, __('Profiles Filtered Successfully'));
    }
}
