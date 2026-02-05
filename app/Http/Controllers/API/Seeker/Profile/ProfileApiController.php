<?php

namespace App\Http\Controllers\API\Seeker\Profile;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Seeker\ProfileResource;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileApiController extends Controller
{
    use ApiResponse;

    /**
     * Get seeker profile details.
     */
    public function profileDetails()
    {
        $user = Auth::user()->load('profile');

        return $this->sendResponse(
            new ProfileResource($user),
            'Profile details retrieved successfully.'
        );
    }

    /**
     * Update seeker profile details (including profile picture).
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'birth_date' => 'nullable|string', 
            'gender' => 'nullable|in:male,female,others',
            'about_us' => 'nullable|string|max:1000',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:20048',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
        }

        $validated = $validator->validated();

        // Update User table fields
        if (isset($validated['name'])) {
            $user->update(['name' => $validated['name']]);
        }

        // Update Profile table fields
        $profile = $user->profile ?: $user->profile()->create();
        
        $profileData = [
            'gender' => $validated['gender'] ?? $profile->gender,
            'about_us' => $validated['about_us'] ?? $profile->about_us,
        ];

        // Handle birth_date format (DD/MM/YYYY)
        if (!empty($validated['birth_date'])) {
            try {
                $profileData['birth_date'] = \Carbon\Carbon::createFromFormat('d/m/Y', $validated['birth_date'])->format('Y-m-d');
            } catch (\Exception $e) {
                // Fallback for standard ISO format or others
                $profileData['birth_date'] = \Carbon\Carbon::parse($validated['birth_date'])->format('Y-m-d');
            }
        }

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old picture
            if ($profile->profile_picture) {
                Helper::deleteFile($profile->profile_picture);
            }

            // Upload new picture
            $profileData['profile_picture'] = Helper::uploadFile('profiles', $request->file('profile_picture'));
        }

        $profile->update($profileData);

        return $this->sendResponse(
            new ProfileResource($user->load('profile')),
            'Profile updated successfully.'
        );
    }
}
