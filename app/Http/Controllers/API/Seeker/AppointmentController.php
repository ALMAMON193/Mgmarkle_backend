<?php

namespace App\Http\Controllers\API\Seeker;

use App\Http\Controllers\Controller;
use App\Models\LeaderBooking;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Carbon;
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

    /**
     * Book a 1-on-1 session with a leader using available Session Credit
     */
    public function bookWithCredit(Request $request)
    {
        $request->validate([
            'leader_id' => 'required|exists:users,id',
            'date'      => 'required|date_format:Y-m-d',
            'time'      => 'required|date_format:H:i',
        ]);

        $user = $request->user();

        // 1. Check if user has at least 1 credit available
        if (($user->available_credits ?? 0) < 1) {
            return $this->sendError(
                'Insufficient credits. You do not have an available session credit. Please purchase a single session or upgrade your membership.',
                ['available_credits' => 0],
                402
            );
        }

        $leader = User::findOrFail($request->leader_id);
        $amount = $leader->session_price > 0 ? $leader->session_price : 50.00;

        $starts_at = Carbon::parse($request->date . ' ' . $request->time);
        $ends_at   = (clone $starts_at)->addHour();

        // 2. Deduct 1 credit from user
        $user->decrement('available_credits', 1);

        // 3. Create Leader Booking with status 'paid'
        $booking = LeaderBooking::create([
            'user_id'   => $user->id,
            'leader_id' => $leader->id,
            'amount'    => $amount,
            'status'    => 'paid',
            'starts_at' => $starts_at,
            'ends_at'   => $ends_at,
        ]);

        return $this->sendResponse([
            'booking_id'        => $booking->id,
            'status'            => $booking->status,
            'remaining_credits' => (int) $user->fresh()->available_credits,
            'starts_at'         => $starts_at->toDateTimeString(),
            'ends_at'           => $ends_at->toDateTimeString(),
        ], 'Session booked successfully using 1 session credit.');
    }
}
