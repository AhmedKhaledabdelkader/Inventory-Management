<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use App\Http\Resources\PickerNotificationResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $notifications = $request->user()->notifications()->latest()->get() ;
      

        return response()->json([
            'status' => 'success',
            'message' => 'Notifications retrieved successfully',
            'result'=>PickerNotificationResource::collection($notifications)

           /* 'result' => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'per_page' => $notifications->perPage(),
                'total' => $notifications->total(),
                'data' => PickerNotificationResource::collection($notifications->getCollection()),
            ],*/
        ]);
    }

    public function unread(Request $request): JsonResponse
    {
        $notifications = $request->user()
            ->unreadNotifications()
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Unread notifications retrieved successfully',
            'result' => PickerNotificationResource::collection($notifications),
        ]);
    }

    public function markAsRead(string $id, Request $request): JsonResponse
    {
        $notification = $request->user()
            ->notifications()
            ->where('id', $id)
            ->first();

        if (!$notification) {
            return response()->json([
                'status' => 'error',
                'message' => 'Notification not found',
            ], 404);
        }

        $notification->markAsRead();

        return response()->json([
            'status' => 'success',
            'message' => 'Notification marked as read successfully',
        ]);
    }

    public function markAllAsRead(Request $request): JsonResponse
    {
        $request->user()
            ->unreadNotifications
            ->markAsRead();

        return response()->json([
            'status' => 'success',
            'message' => 'All notifications marked as read successfully',
        ]);
    }

    public function unreadCount(Request $request): JsonResponse
    {
        $count = $request->user()
            ->unreadNotifications()
            ->count();

        return response()->json([
            'status' => 'success',
            'message' => 'Unread notifications count retrieved successfully',
            'result' => [
                'unread_count' => $count,
            ],
        ]);
    }
}