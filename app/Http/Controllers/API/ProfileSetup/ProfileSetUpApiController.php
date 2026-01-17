<?php

namespace App\Http\Controllers\API\ProfileSetup;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileSetUpRequest;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;

class ProfileSetUpApiController extends Controller
{
    use ApiResponse;

    public function store(ProfileSetUpRequest $request)
    {
        $user = Auth::user();
        $validatedData = $request->validated();

        $subCategoryIds = $validatedData['sub_categories'] ?? [];

        unset($validatedData['sub_categories']);

        if ($request->hasFile('profile_picture')) {
            $validatedData['profile_picture'] = Helper::uploadFile('profiles', $request->file('profile_picture'));
        }

        $profile = Profile::updateOrCreate(
            ['user_id' => $user->id],
            $validatedData
        );

        $profile->subCategories()->sync($subCategoryIds);

        return $this->sendResponse(
            new ProfileResource($profile->load('subCategories')),
            __('Profile Setup Successfully')
        );
    }

    public function show()
    {
        $profile = Auth::user()->profile;

        if (! $profile) {
            return $this->sendResponse([], __('User Profile Not Setup'));
        }

        return $this->sendResponse(
            new ProfileResource($profile),
            __('Profile Setup Successfully')
        );
    }
}
