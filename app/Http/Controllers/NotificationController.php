<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller {

    public function getNotifications(){
        try {
            return Notification::with(['office', 'service', 'event'])
                ->where('status', 'approved')
                ->latest()
                ->get();
        } catch (\Exception $e) {
            Log::error("Error loading notifications: " . $e->getMessage());
            return response()->json(['error' => 'Failed to load notifications'], 500);
        }
    }
}
