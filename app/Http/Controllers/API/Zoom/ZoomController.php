<?php

namespace App\Http\Controllers\API\Zoom;

use App\Http\Controllers\Controller;
use App\Services\ZoomService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ZoomController extends Controller
{
    use ApiResponse;

    protected $zoom;

    public function __construct(ZoomService $zoom)
    {
        $this->zoom = $zoom;
    }

    public function create(Request $request)
    {

        $request->validate([
            'topic' => 'required|string|max:255',
            'start_time' => 'required|date',
            'duration' => 'required|integer|min:15',
        ]);

        try {

            $meetingData = [
                'topic' => $request->topic,
                'type' => 2,
                'start_time' => date('Y-m-d\TH:i:s', strtotime($request->start_time)),
                'duration' => $request->duration,
                'timezone' => 'Asia/Dhaka',
                'settings' => [
                    'host_video' => true,
                    'participant_video' => true,
                    'join_before_host' => false,
                    'waiting_room' => true,
                ],
            ];

            $meeting = $this->zoom->createMeeting($meetingData);

            if (isset($meeting['id'])) {
                return $this->sendResponse($meeting, 'Meeting created successfully');
            }

            return $this->sendError('Meeting creation failed');

        } catch (\Exception $e) {
            Log::error('Zoom Creation Error: '.$e->getMessage());

            return $this->sendError('Meeting creation failed');
        }
    }

    public function list()
    {
        $meetings = $this->zoom->listMeetings();

        return $this->sendResponse($meetings, 'Meetings List');
    }

    public function delete($id)
    {
        $success = $this->zoom->deleteMeeting($id);

        return $this->sendResponse($success, 'Meeting Deleted');
    }
}
