<?php

namespace App\Http\Controllers\API\SpiritualGuide\Profile;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\AvailableSlotsResponse;
use App\Http\Resources\SpiritualGuide\ProfileResource;
use App\Models\Availability;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileApiController extends Controller
{
    use ApiResponse;

    // available slots for spiritual guide
    public function availableSlots()
    {
        $slots = Availability::where('user_id', auth()->user()->id)
            ->where('status', 'available')
            ->orderBy('created_at', 'desc')
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return $this->sendResponse(
            AvailableSlotsResponse::collection($slots),
            'Available slots retrieved successfully.'
        );
    }

    // add slot
    public function addSlot(Request $request)
    {
        // ✅ 1. Validate the request
        $validator = Validator::make($request->all(), [
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|string|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        //  2. Detect day name automatically
        $day = Carbon::parse($request->date)->format('l');

        //  3. Prevent overlapping slots for same user
        $conflict = Availability::where('user_id', auth()->id())
            ->where('date', $request->date)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_time', '<', $request->start_time)
                            ->where('end_time', '>', $request->end_time);
                    });
            })
            ->exists();

        if ($conflict) {
            return $this->sendError('Time conflict detected. This slot overlaps with an existing availability.', [], 409);
        }

        //  4. Create the slot
        $availability = Availability::create([
            'user_id' => auth()->id(),
            'date' => $request->date,
            'day' => $day,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => 'available',
        ]);

        //  5. Return a formatted API resource
        return $this->sendResponse(
            new AvailableSlotsResponse($availability),
            'Availability slot created successfully.'
        );
    }

    // profile details
    public function profileDetails()
    {
        $profileDetails = auth()->user()->load(['profile.category', 'profile.subCategories', 'availableSlots']);

        return $this->sendResponse(
            new ProfileResource($profileDetails),

            'Profile details retrieved successfully.'
        );
    }

    // update profile
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'about_us' => 'nullable|string|max:1000',
            'profile_picture' => 'nullable|string|max:255',
            'affiliated_offer' => 'nullable|string|max:255',
            'topic_offer' => 'nullable|array',
            'categories' => 'nullable|array',
            'sub_categories' => 'nullable|array',
        ]);

        // Create profile if missing
        $profile = $user->profile ?: $user->profile()->create();

        // Update profile
        $profile->update([
            'birth_date' => $validated['birth_date'] ?? $profile->birth_date,
            'gender' => $validated['gender'] ?? $profile->gender,
            'about_us' => $validated['about_us'] ?? $profile->about_us,
            'profile_picture' => $validated['profile_picture'] ?? $profile->profile_picture,
            'affiliated_offer' => $validated['affiliated_offer'] ?? $profile->affiliated_offer,
            'topic_offer' => $validated['topic_offer'] ?? $profile->topic_offer,
            'category_id' => isset($validated['categories']) && ! empty($validated['categories']) ? $validated['categories'][0] : $profile->category_id,
        ]);

        if (isset($validated['sub_categories'])) {
            $profile->subCategories()->sync($validated['sub_categories']);
        }

        $updatedUser = $user->load(['profile.category', 'profile.subCategories', 'availableSlots']);

        return $this->sendResponse(
            new ProfileResource($updatedUser),
            'Profile updated successfully.');
    }

    // profile picture update
    public function updateProfilePicture(Request $request)
    {
        $user = auth()->user();

        // Validate the uploaded file
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        // Create profile if missing
        $profile = $user->profile ?: $user->profile()->create();

        if ($request->hasFile('profile_picture')) {

            // Delete existing profile picture if exists
            Helper::deleteFile($profile->profile_picture);

            // Upload new profile picture
            $filePath = Helper::uploadFile('profiles', $request->file('profile_picture'));

            // Update profile
            $profile->update([
                'profile_picture' => $filePath,
            ]);
        }

        // Reload user with profile
        $updatedUser = $user->load('profile');

        return $this->sendResponse(
            [],
            'Profile picture updated successfully.');
    }
}
