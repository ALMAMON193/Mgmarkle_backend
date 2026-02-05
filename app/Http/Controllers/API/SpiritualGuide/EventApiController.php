<?php

namespace App\Http\Controllers\API\SpiritualGuide;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SpiritualGuide\StoreRequest;
use App\Http\Requests\SpiritualGuide\UpdateRequest;
use App\Http\Resources\SpiritualGuide\ListResource;
use App\Http\Resources\SpiritualGuide\StoreResource;
use App\Models\Event;
use App\Models\EventBooking;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EventApiController extends Controller
{
    use ApiResponse;

    public function eventList(Request $request)
    {
        $filter = $request->query('event_filter');
        $today = Carbon::today();

        $query = Event::query()->orderBy('created_at', 'desc');

        // Nullify zoom_session_id for expired events before fetching
        Event::whereNotNull('zoom_session_id')
            ->where(function ($query) {
                $query->where('date', '<', now()->toDateString())
                    ->orWhere(function ($q) {
                        $q->where('date', '=', now()->toDateString())
                            ->where('end_time', '<', now()->format('H:i'));
                    });
            })
            ->update(['zoom_session_id' => null]);

        if ($filter === 'weekly') {
            $query->whereBetween('date', [$today->startOfWeek()->toDateString(), $today->endOfWeek()->toDateString()]);
        } elseif ($filter === 'monthly') {
            $query->whereMonth('date', $today->month)
                ->whereYear('date', $today->year);
        }

        // Update EventBooking status to expired for those that have passed
        EventBooking::where('status', '!=', 'expired')
            ->where('ends_at', '<', now())
            ->update(['status' => 'expired']);

        $events = $query->get();

        return $this->sendResponse(
            ListResource::collection($events),
            __('Events Fetched Successfully')
        );
    }

    public function store(StoreRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();

        // Handle image if exists
        if ($request->hasFile('image')) {
            $data['image'] = Helper::uploadFile('events', $request->file('image'));
        }

        try {
            // Store event
            $event = Event::create(array_merge($data, [
                'user_id' => $user->id,
            ]));

            return $this->sendResponse(
                new StoreResource($event),
                __('Event Created Successfully')
            );

        } catch (Exception $e) {
            Log::error('Event Creation Error: '.$e->getMessage());

            return $this->sendError('Error Creating Event');
        }
    }

    public function update(UpdateRequest $request, Event $event)
    {
        $user = Auth::user();
        $data = $request->validated();

        if ($event->user_id !== $user->id) {
            return $this->sendError('Unauthorized');
        }

        // Handle image update
        if ($request->hasFile('image')) {
            if ($event->image) {
                Helper::deleteFile($event->image);
            }
            $data['image'] = Helper::uploadFile('events', $request->file('image'));
        }

        try {
            $event->update($data);

            return $this->sendResponse(
                new StoreResource($event),
                __('Event Updated Successfully')
            );
        } catch (Exception $e) {
            Log::error('Event Update Error: '.$e->getMessage());

            return $this->sendError('Error Updating Event');
        }
    }

    public function show($id)
    {
        try {
            $event = Event::with('user')->findOrFail($id);

            // Check if expired and nullify zoom_session_id
            $eventEndTime = Carbon::parse($event->date->format('Y-m-d') . ' ' . $event->end_time);
            if (now()->gt($eventEndTime) && !is_null($event->zoom_session_id)) {
                $event->update(['zoom_session_id' => null]);
            }

            return $this->sendResponse(
                new StoreResource($event),
                __('Event Details Retrieved Successfully')
            );
        } catch (Exception $e) {
            Log::error('Event Details Error: '.$e->getMessage());

            return $this->sendError('Event Not Found', [], 404);
        }
    }

    public function eventDelete($id)
    {
        $event = Event::find(intval($id));
        if (! $event) {
            return $this->sendError('Event Not Found');
        }
        // remove image if exists
        if ($event->image) {
            $event->delete();
        }
        $event->delete();

        return $this->sendResponse(
            [],
            __('Event Deleted Successfully')
        );
    }
}
