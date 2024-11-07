<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    public function index()
    {
        // Fetch all approved events
        $approvedEvents = Event::where('status', 'approved')->get();
        return view('pages.events', compact('approvedEvents'));
    }

    public function store(Request $request)
{
    // Validate the request
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'image' => 'required|url', // Validate as a URL
    ]);

    try {
        // Create the event
        $event = new Event();
        $event->title = $request->title;
        $event->description = $request->description;
        $event->image = $request->image; // Save the image URL into the 'image' column
        $event->status = 'pending';
        $event->user_id = auth()->id(); // Set the user_id to the authenticated user
        $event->save();

        return redirect()->route('events.page')->with('success', 'Event created and waiting for approval.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Failed to create event.');
    }
}

    public function showPendingEvents()
    {
        // Fetch pending events for the admin to approve or reject
        $pendingEvents = Event::where('status', 'pending')->get();
        return view('pendings-folder.pending-events', compact('pendingEvents'));
    }

    public function approveEvent($id)
    {
        // Approve the event
        $event = Event::findOrFail($id);
        $event->status = 'approved';
        $event->save();

        Notification::create([
        'event_id' => $event->id,
        'status' => 'approved',
    ]);
        
        return redirect()->route('pending.events')->with('success', 'Event approved successfully.');
    }

    public function rejectEvent($id)
    {
        // Reject the event
        $event = Event::findOrFail($id);
        $event->status = 'rejected';
        $event->save();

        Notification::create([
            'event_id' => $event->id,
            'status' => 'rejected',
        ]);
        
        return redirect()->route('pending.events')->with('success', 'Event rejected successfully.');
    }
}
