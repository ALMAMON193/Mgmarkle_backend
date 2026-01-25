<?php

namespace App\Http\Controllers\API\Seeker;

use App\Http\Controllers\Controller;
use App\Models\LeaderBooking;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    use ApiResponse;

    /**
     * List all appointments for the seeker
     */
    public function index(Request $request)
    {
        $appointments = LeaderBooking::where('user_id', $request->user()->id)
            ->with(['leader.profile'])
            ->orderBy('starts_at', 'desc')
            ->get();

        $data = $appointments->map(function ($appointment) {
            return [
                'id' => $appointment->id,
                'leader' => [
                    'id' => $appointment->leader->id,
                    'name' => $appointment->leader->name,
                    'avatar' => $appointment->leader->profile?->profile_picture ? asset($appointment->leader->profile->profile_picture) : null,
                ],
                'date' => $appointment->starts_at->format('d F, Y'),
                'time' => $appointment->starts_at->format('h:i A').' - '.$appointment->ends_at->format('h:i A'),
                'status' => $appointment->status,
                'amount' => $appointment->amount,
            ];
        });

        return $this->sendResponse($data, 'Appointments retrieved successfully.');
    }

    /**
     * Show appointment details
     */
    public function show($id)
    {
        $appointment = LeaderBooking::where('user_id', auth()->id())
            ->with(['leader.profile', 'user.profile'])
            ->find($id);

        if (! $appointment) {
            return $this->sendError('Appointment not found.', [], 404);
        }

        $seeker = $appointment->user;
        $leader = $appointment->leader;

        $now = now();
        $displayStatus = $appointment->status;

        if ($appointment->status === 'paid') {
            if ($now->lt($appointment->starts_at)) {
                $displayStatus = 'Upcoming';
            } elseif ($now->between($appointment->starts_at, $appointment->ends_at)) {
                $displayStatus = 'Ongoing';
            } else {
                $displayStatus = 'Completed';
            }
        }

        $data = [
            'id' => $appointment->id,
            'status' => ucfirst($appointment->status),
            'display_status' => $displayStatus,
            'leader' => [
                'id' => $leader->id,
                'name' => $leader->name,
                'fellowship' => $leader->profile?->category?->name ?? 'Grace Fellowship', // Placeholder or use category
                'avatar' => $leader->profile?->profile_picture ? asset($leader->profile->profile_picture) : null,
                'session_price' => $leader->session_price,
            ],
            'schedule' => [
                'date' => $appointment->starts_at->format('l, F d, Y'),
                'time' => $appointment->starts_at->format('h:i').' - '.$appointment->ends_at->format('h:i A'),
                'duration' => $appointment->starts_at->diffInMinutes($appointment->ends_at).' minutes',
            ],
            'member_info' => [
                'full_name' => $seeker->name,
                'gender' => $seeker->profile?->gender ?? 'N/A',
                'age' => $seeker->profile?->birth_date ? $seeker->profile->birth_date->age : 'N/A',
                'topic' => $seeker->profile?->about_us ?? 'N/A', // Using about_us as topic
            ],
            'package' => [
                'type' => 'Video Call',
                'price' => $appointment->amount,
                'status' => ucfirst($appointment->status),
            ],
            'zoom' => [
                'zoom_meeting_id' => $appointment->zoom_meeting_id,
                'join_url' => $appointment->zoom_join_url,
                'start_time_text' => 'Video Call (Start at '.$appointment->starts_at->format('h:i A').')',
            ],
        ];

        return $this->sendResponse($data, 'Appointment details retrieved successfully.');
    }

    /**
     * Update Zoom details from Flutter SDK
     */
    public function updateZoom(Request $request, $id)
    {
        $request->validate([
            'zoom_meeting_id' => 'required|string',
            'zoom_join_url' => 'required|url',
        ]);

        // Allows both the Seeker and the Leader to update the zoom info
        $appointment = LeaderBooking::where(function ($query) {
            $query->where('user_id', auth()->id())
                ->orWhere('leader_id', auth()->id());
        })->find($id);

        if (! $appointment) {
            return $this->sendError('Appointment not found or unauthorized.', [], 404);
        }

        $appointment->update([
            'zoom_meeting_id' => $request->zoom_meeting_id,
            'zoom_join_url' => $request->zoom_join_url,
        ]);

        return $this->sendResponse([], 'Zoom details updated successfully.');
    }
}
