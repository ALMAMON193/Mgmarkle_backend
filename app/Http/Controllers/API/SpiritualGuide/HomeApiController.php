<?php

namespace App\Http\Controllers\API\SpiritualGuide;

use App\Http\Controllers\Controller;
use App\Http\Resources\SpiritualGuide\BookingResource;
use App\Http\Resources\SpiritualGuide\HomeResource;
use App\Http\Resources\SpiritualGuide\HomeScheduleResource;
use App\Models\Event;
use App\Models\EventBooking;
use App\Notifications\BookingAcceptedNotification;
use App\Notifications\BookingDeclinedNotification;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeApiController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();

        $data = [
            'today_schedule' => EventBooking::where('leader_id', $user->id)
                ->where('booking_status', 'accepted')
                ->whereDate('starts_at', $today)
                ->with(['user.profile', 'event'])
                ->get(),

            'new_booking_request' => EventBooking::where('leader_id', $user->id)
                ->where('booking_status', 'ongoing')
                ->with(['user.profile', 'event'])
                ->latest()
                ->take(3)
                ->get(),

            'upcoming_request' => Event::where('user_id', $user->id)
                ->whereDate('date', '>', $today)
                ->with(['user.profile'])
                ->orderBy('date')
                ->take(5)
                ->get(),
        ];

        return $this->sendResponse(
            new HomeResource($data),
            __('Home Data Retrieved Successfully')
        );
    }

    // accept booking request
    public function acceptBookingRequest($id)
    {
        $booking = EventBooking::with('user', 'event')->find($id);
        if (! $booking) {
            return $this->sendError('Booking Not Found');
        }
        $booking->update([
            'booking_status' => 'accepted',
        ]);

        // Send Notification
        if ($booking->user) {
            $booking->user->notify(new BookingAcceptedNotification($booking));
        }

        return $this->sendResponse(
            [],
            __('Booking Request Accepted Successfully')
        );
    }

    // decline booking request
    public function declineBookingRequest($id)
    {
        $booking = EventBooking::with('user', 'event')->find($id);
        if (! $booking) {
            return $this->sendError('Booking Not Found');
        }
        $booking->update([
            'booking_status' => 'declined',
        ]);

        // Send Notification
        if ($booking->user) {
            $booking->user->notify(new BookingDeclinedNotification($booking));
        }

        return $this->sendResponse(
            [],
            __('Booking Request Declined Successfully')
        );
    }

    // List all booking requests for the guide
    public function bookingRequests()
    {
        $user = Auth::user();

        $requests = EventBooking::where('leader_id', $user->id)
            ->where('booking_status', 'ongoing')
            ->with(['user.profile', 'event'])
            ->latest()
            ->get();

        return $this->sendResponse(
            BookingResource::collection($requests),
            __('Booking Requests Retrieved Successfully')
        );
    }

    // List all accepted schedules for the guide (with search)
    public function allSchedule()
    {
        $user = Auth::user();
        $search = request('search');

        $schedules = EventBooking::where('leader_id', $user->id)
            ->where('booking_status', 'accepted')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('event', function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                })->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->with(['user.profile', 'event'])
            ->orderBy('starts_at', 'desc')
            ->get();

        return $this->sendResponse(
            HomeScheduleResource::collection($schedules)->response()->getData(true),
            __('All Schedules Retrieved Successfully')
        );
    }
}
