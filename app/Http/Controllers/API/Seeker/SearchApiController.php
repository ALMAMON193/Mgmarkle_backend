<?php

namespace App\Http\Controllers\API\Seeker;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SearchApiController extends Controller
{
    use ApiResponse;

    public function searchProfiles(Request $request)
    {
        // Create a unique cache key based on request parameters
        $cacheKey = 'search_profiles_'.md5(json_encode($request->all()));

        $apiResponse = Cache::remember($cacheKey, 60 * 5, function () use ($request) { // Cache for 5 minutes
            $query = Profile::query()->with(['user', 'category', 'subCategory']);

            if ($request->filled('user_name')) {
                $query->whereHas('user', function ($q) use ($request) {
                    $q->where('name', 'like', '%'.$request->user_name.'%');
                });
            }

            if ($request->filled('leader')) {
                $query->where('leader', 'like', '%'.$request->leader.'%');
            }

            return $query->get()->map(function ($profile) {
                $avgRating = \App\Models\Rating::where('user_id', $profile->user_id)->avg('rating');

                return [
                    'name' => $profile->user->name,
                    'category' => $profile->category->name,
                    'avatar' => $profile->profile_picture ? Helper::generateURL($profile->profile_picture) : null,
                    'average_rating' => round($avgRating, 1),
                ];
            });
        });

        return $this->sendResponse($apiResponse, __('Profiles Retrieved Successfully'));
    }

    public function filterProfiles(Request $request)
    {
        // Create a unique cache key based on all incoming filter parameters
        $cacheKey = 'filter_profiles_'.md5(json_encode($request->all()));

        $profiles = Cache::remember($cacheKey, now()->addMinutes(1), function () use ($request) {

            // Base query with relationships
            $query = Profile::with(['user', 'category', 'subCategory']);

            // Filter by Category Name
            if ($request->filled('category_name')) {
                $query->whereHas('category', function ($q) use ($request) {
                    $q->where('name', 'like', '%'.$request->category_name.'%');
                });
            }

            // Filter by Sub Category Name
            if ($request->filled('sub_category_name')) {
                $subCategoryName = urldecode($request->sub_category_name);
                $query->whereHas('subCategory', function ($q) use ($subCategoryName) {
                    $q->where('name', 'like', '%'.$subCategoryName.'%');
                });
            }

            // Filter by Minimum Average Rating for events created by the user
            if ($request->filled('rating')) {
                $rating = $request->rating;
                $query->whereHas('user', function ($q) use ($rating) {
                    $q->whereHas('events', function ($ev) use ($rating) {
                        $ev->join('ratings', 'ratings.event_id', '=', 'events.id')
                            ->select('events.user_id', DB::raw('AVG(ratings.rating) as avg_rating'))
                            ->groupBy('events.user_id')
                            ->havingRaw('AVG(ratings.rating) >= ?', [$rating]);
                    });
                });
            }

            // Execute the query
            $profilesData = $query->get();

            // Map results to API response format
            return $profilesData->map(function ($profile) {
                // Calculate average rating for events created by this user
                $avgRating = $profile->user->events()
                    ->with('ratings')
                    ->get()
                    ->pluck('ratings')
                    ->flatten()
                    ->avg('rating');

                return [
                    'name' => $profile->user->name ?? null,
                    'category' => $profile->category->name ?? null,
                    'sub_category' => $profile->subCategory->name ?? null,
                    'avatar' => $profile->profile_picture ? Helper::generateURL($profile->profile_picture) : null,
                    'average_rating' => round($avgRating ?? 0, 1),
                ];
            });
        });

        return $this->sendResponse($profiles, __('Profiles Filtered Successfully'));
    }
}
