<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
{
    $notifications = Notification::where('user_id', auth()->id())
        ->orderBy('is_read', 'asc')
        ->orderBy('dateTime', 'desc')
        ->paginate(10); // Paginate results

    $unreadCount = $notifications->where('is_read', false)->count();

    if ($request->ajax()) {
        return response()->json([
            'notifications' => $notifications->items(), // Return paginated data
            'unreadCount' => $unreadCount,
        ]);
    }

    return view('notifications.index', compact('notifications', 'unreadCount'));
}

public function markAllAsRead()
{
    $user = auth()->user();

    // Update all notifications for the authenticated user
    Notification::where('user_id', $user->id)->update(['is_read' => true]);

    return response()->json(['success' => true]);
}



    // Mark notification as read
    public function markAsRead($id)
    {
        // Find the notification by ID
        $notification = Notification::findOrFail($id);

        // Mark the notification as read
        $notification->is_read = true;
        $notification->save();

        // Redirect to the notification link if available
        if ($notification->link) {
            return redirect($notification->link);
        }

        // Fallback: redirect to notifications page if no specific link is present
        return redirect()->route('notifications.index');
    }

    public function fetchNotifications(Request $request)
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('is_read', 'asc')
            ->orderBy('created_at', 'desc')
            ->take(10) // Limit to the latest 10 notifications
            ->get();

        $unreadCount = $notifications->where('is_read', false)->count();

        return response()->json([
            'notifications' => $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'description' => $notification->description,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'is_read' => $notification->is_read,
                ];
            }),
            'unreadCount' => $unreadCount,
        ]);
    }



}
