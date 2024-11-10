<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
{
    $notifications = Notification::where('user_id', auth()->id())->get();
    $unreadCount = Notification::where('user_id', auth()->id())->where('is_read', false)->count(); // Get unread notifications count
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

        // Redirect to the notification link or fallback to dashboard
        return redirect($notification->link ?? route('pending.events')); // Fallback to dashboard if link is empty
    }


}
