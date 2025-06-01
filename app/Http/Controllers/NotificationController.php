<?php

namespace App\Http\Controllers;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(5)  // Adjust number as needed
            ->through(function ($notification) {
                return [
                    'id' => $notification->id,
                    'message' => $notification->data['message'] ?? '',
                    'url' => $notification->data['url'] ?? '',
                    'read' => !is_null($notification->read_at),
                    'created_at' => $notification->created_at->diffForHumans()
                ];
            });

        return response()->json($notifications);
    }


    public function markAsRead($id)
    {
        $notification = auth()->user()
            ->notifications()
            ->where('id', $id)
            ->first();

        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }
}
