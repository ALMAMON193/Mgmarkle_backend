<?php

namespace App\Http\Controllers\API\SpiritualGuide;

use App\Http\Controllers\Controller;
use App\Http\Resources\SpiritualGuide\AppointmentListResource;
use App\Http\Resources\SpiritualGuide\BookingResource;
use App\Http\Resources\SpiritualGuide\HomeResource;
use App\Http\Resources\SpiritualGuide\HomeScheduleResource;
use App\Models\Event;
use App\Models\EventBooking;
use App\Notifications\BookingAcceptedNotification;
use App\Notifications\BookingDeclinedNotification;
use App\Notifications\BookingRescheduledNotification;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
            ->orderBy('starts_at', 'desc')
            ->get();

        return $this->sendResponse(
            HomeScheduleResource::collection($schedules),
            __('All Schedules Retrieved Successfully')
        );
    }

    /**
     * List all appointments for the guide (with tabs and search)
     */
    public function allAppointments()
    {
        $user = Auth::user();
        $search = request('search');
        $status = request('status', 'upcoming'); // upcoming, completed, cancelled

        $query = EventBooking::where('leader_id', $user->id)
            ->with(['user.profile', 'event']);

        if ($status === 'upcoming') {
            $query->where('booking_status', 'accepted')
                ->where('ends_at', '>=', now());
        } elseif ($status === 'completed') {
            $query->where(function ($q) {
                $q->where('booking_status', 'completed')
                    ->orWhere(function ($sq) {
                        $sq->where('booking_status', 'accepted')
                            ->where('ends_at', '<', now());
                    });
            });
        } elseif ($status === 'cancelled') {
            $query->where(function ($q) {
                $q->where('status', 'cancelled')
                    ->orWhere('booking_status', 'declined');
            });
        }

        $appointments = $query->when($search, function ($query) use ($search) {
            $query->whereHas('event', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            })->orWhereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        })
            ->orderBy('starts_at', 'desc')
            ->get();

        return $this->sendResponse(
            AppointmentListResource::collection($appointments),
            __('Appointments Retrieved Successfully')
        );
    }

    /**
     * Reschedule a booking by spiritual guide
     */
    public function rescheduleBooking(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'reason' => 'nullable|string',
        ]);

        $booking = EventBooking::where('leader_id', auth()->id())->with('user', 'event')->find($id);
        if (! $booking) {
            return $this->sendError('Booking Not Found');
        }

        try {
            $startsAt = Carbon::parse($request->date.' '.$request->start_time);
            $endsAt = Carbon::parse($request->date.' '.$request->end_time);
        } catch (\Exception $e) {
            return $this->sendError('Invalid date or time format.');
        }

        $booking->update([
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'reschedule_reason' => $request->reason,
            'booking_status' => 'accepted', // Keep it accepted if rescheduled by guide
        ]);

        // Notify the seeker
        if ($booking->user) {
            $booking->user->notify(new BookingRescheduledNotification($booking));
        }

        return $this->sendResponse(
            new HomeScheduleResource($booking),
            __('Booking Rescheduled Successfully')
        );
    }
}
