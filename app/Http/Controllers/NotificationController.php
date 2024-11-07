<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getNotifications(){
        $notifications = Notification::orderBy('created_at', 'desc')->get();
        return response()->json($notifications);
    }

}
