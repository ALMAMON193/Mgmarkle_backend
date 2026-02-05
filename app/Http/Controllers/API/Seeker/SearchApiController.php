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
        $query = Profile::query()
            ->whereHas('user', function ($q) {
                $q->where('user_type', 'spiritual_guide');
            })
            ->with(['user', 'category', 'subCategories']);

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
            $user = $profile->user;

            return [
                'user_id' => $profile->user_id,
                'name' => $user->name ?? 'N/A',
                'affiliation' => $profile->school_name ?? 'Grace Fellowship',
                'status' => $user->is_online ? 'Available Now' : 'Offline',
                'price' => '$'.number_format($user->session_price, 0).'/1 hour',
                'category' => $profile->category->name ?? null,
                'sub_categories' => $profile->subCategories->pluck('name'),
                'avatar' => $profile->profile_picture ? Helper::generateURL($profile->profile_picture) : 'https://ui-avatars.com/api/?name='.urlencode($user->name ?? 'User').'&color=7F9CF5&background=EBF4FF',
                'average_rating' => $user->averageRating(),
                'is_online' => $user->is_online ?? false,
            ];
        });

        return $this->sendResponse($profiles, __('Profiles Retrieved Successfully'));
    }

    public function filterProfiles(Request $request)
    {
        // Base query with relationships
        $query = Profile::query()
            ->whereHas('user', function ($q) {
                $q->where('user_type', 'spiritual_guide');
            })
            ->with(['user', 'category', 'subCategories']);

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

        // Filter by Online/Offline Status
        if ($request->filled('is_online')) {
            $isOnline = filter_var($request->is_online, FILTER_VALIDATE_BOOLEAN);
            $query->whereHas('user', function ($q) use ($isOnline) {
                $q->where('is_online', $isOnline);
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
        $data = $profiles->values()->map(function ($profile) {
            $user = $profile->user;

            return [
                'user_id' => $profile->user_id,
                'name' => $user->name ?? 'N/A',
                'affiliation' => $profile->school_name ?? 'Grace Fellowship',
                'status' => $user->is_online ? 'Available Now' : 'Offline',
                'price' => '$'.number_format($user->session_price, 0).'/1 hour',
                'category' => $profile->category->name ?? null,
                'sub_categories' => $profile->subCategories->pluck('name'),
                'avatar' => $profile->profile_picture ? Helper::generateURL($profile->profile_picture) : 'https://ui-avatars.com/api/?name='.urlencode($user->name ?? 'User').'&color=7F9CF5&background=EBF4FF',
                'average_rating' => $user->averageRating(),
                'is_online' => $user->is_online ?? false,
            ];
        });

        return $this->sendResponse($data, __('Profiles Filtered Successfully'));
    }
}
