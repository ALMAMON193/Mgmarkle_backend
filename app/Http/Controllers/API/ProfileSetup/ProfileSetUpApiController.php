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

        $data = $request->validated();
        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $data['profile_picture'] = Helper::uploadFile('profiles', $request->file('profile_picture'));
        }

        // Store or update profile
        $profile = Profile::updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

        return $this->sendResponse(
            new ProfileResource($profile),
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
