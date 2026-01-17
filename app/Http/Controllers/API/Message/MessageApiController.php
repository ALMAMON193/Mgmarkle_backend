<?php

namespace App\Http\Controllers\API\Message;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class MessageApiController extends Controller
{
    use ApiResponse;

    // Send Message
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'nullable|string',
            'file' => 'nullable|image|max:20480', // 20MB
        ]);

        $filePath = null;
        $fileType = null;

        if ($request->hasFile('file')) {
            $filePath = Helper::uploadFile('chatImage', $request->file('file'));
            $fileType = $request->file->getClientOriginalExtension();
        }

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->content,
            'file' => $filePath,
            'file_type' => $fileType,
        ]);

        return $this->sendResponse(
            [],
            'Message sent successfully.'
        );
    }

    // User list except logged in user
    public function userList()
    {
        $users = User::where('user_type', '!=', 'admin')->get();
        // api response
        $users = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => Helper::generateURL($user->avatar) ?? '',
            ];
        });

        return $this->sendResponse($users, 'Users retrieved successfully.');
    }

    // Get chat between sender & receiver
    public function getUserMessages($userId)
    {
        $messages = Message::where(function ($q) use ($userId) {
            $q->where('sender_id', auth()->id())
                ->where('receiver_id', $userId);
        })
            ->orWhere(function ($q) use ($userId) {
                $q->where('sender_id', $userId)
                    ->where('receiver_id', auth()->id());
            })
            ->orderBy('created_at', 'ASC')
            ->get();
        // api response
        $messages = $messages->map(function ($message) {
            return [
                'id' => $message->id,
                'sender_id' => $message->sender_id,
                'receiver_id' => $message->receiver_id,
                'content' => $message->content,
                'file' => Helper::generateURL($message->file) ?? '',
                'file_type' => $message->file_type,
                'is_read' => $message->is_read,
                'created_at' => $message->created_at->toDateTimeString(),
            ];
        });

        return $this->sendResponse($messages, 'Messages retrieved successfully');
    }
}
