<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
{
    $notifications = Notification::where('user_id', auth()->id())
        ->orderBy('is_read', 'asc')
        ->orderBy('dateTime', 'desc')
        ->get();
    $unreadCount = $notifications->where('is_read', false)->count();
    return view('notifications.index', compact('notifications', 'unreadCount'));
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




}
