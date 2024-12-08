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
    // Fetch approved events that are not expired
    $approvedEvents = Event::where('status', 'approved')
        ->where('expires_at', '>', now()) // Exclude expired events
        ->orderBy('created_at', 'desc')
        ->get();

    // Fetch pending events that are not expired
    $pendingEvents = Event::where('status', 'pending')
        ->where('user_id', auth()->id())
        ->where('expires_at', '>', now()) // Exclude expired events
        ->orderBy('created_at', 'desc')
        ->get();

    // Fetch expired events (archived)
    $archivedEvents = Event::where('expires_at', '<', now()) // Only expired events
        ->orderBy('expires_at', 'desc')
        ->get();

    return view('pages.events', compact('approvedEvents', 'pendingEvents', 'archivedEvents'));
}


public function archived()
{
    // Fetch archived (expired) events
    $archivedEvents = Event::where('expires_at', '<', now()) // Only expired events
        ->orderBy('expires_at', 'desc')
        ->get();

    return view('pages.archived', compact('archivedEvents'));
}


    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'image' => 'nullable|string|url', // Allow nullable for image URL
        'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000', // Allow file upload
        'date_time' => 'required|date',
        'expires_at' => 'required|date',
    ]);

    try {
        $event = new Event();
        $event->title = $request->title;
        $event->description = $request->description;
        $event->date_time = $request->date_time;
        $event->expires_at = $request->expires_at;
        $event->user_id = auth()->id();

        // Check if a file is uploaded
        if ($request->hasFile('image_file')) {
            // Save the file and store its path
            $imagePath = $request->file('image_file')->store('events', 'public'); // Save to 'storage/app/public/events'
            $event->image = "/storage/$imagePath"; // Store public path in the 'image' column
        } elseif ($request->filled('image')) {
            // Use the image URL if provided
            $event->image = $request->image;
        } else {
            return redirect()->back()->with('error', 'Please provide an image URL or upload an image file.');
        }

        // Set status based on user role
        if (auth()->user()->hasRole('admin')) {
            $event->status = 'approved';
            $message = 'Event created successfully.';
        } else {
            $event->status = 'pending';
            $message = 'Event created and waiting for approval.';

            $user = auth()->user();
            $officeName = $user->office ? $user->office->office_name : 'Unknown Office';
            // Create a notification for admin
            Notification::create([
                'title' => 'New Event Pending Approval',
                'description' => "<strong>{$user->username}</strong> from the <strong>{$officeName}</strong> created a new event, awaiting your approval.",
                'dateTime' => now(),
                'user_id' => 1, // Assuming admin ID is 1
                'link' => route('pending.events'), // Link to pending events page
            ]);
        }

        $event->save();

        return redirect()->route('events.page')->with('success', $message);
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Failed to create event.');
    }
}


    public function show($id)
    {
        $event = Event::findOrFail($id);
        return view('pages.view-events', compact('event'));
    }

    public function showPendingEvent($id)
{
    $event = Event::where('id', $id)->where('status', 'pending')->firstOrFail();

    return view('pendings-folder.view-pending-event', compact('event'));
}

// In EventController

public function showExpiredEvent($id)
{
    // Find the archived event by its ID
    $event = Event::findOrFail($id);

    return view('pages.show-archived-events', compact('event'));
}

    public function edit($id)
{
    $event = Event::findOrFail($id);

    // Check if the logged-in user is the creator or an admin
    if (!auth()->user()->hasRole('admin') && $event->user_id !== auth()->id()) {
        return response()->json(['error' => 'Unauthorized action'], 403);
    }

    return response()->json($event);
}

    public function update(Request $request, $id)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'image' => 'nullable|string|url',
        'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'date_time' => 'required|date',
        'expires_at' => 'required|date',
    ]);

    $event = Event::findOrFail($id);
    $event->title = $request->title;
    $event->description = $request->description;
    $event->date_time = $request->date_time;
    $event->expires_at = $request->expires_at;

    if ($request->hasFile('image_file')) {
        $imagePath = $request->file('image_file')->store('events', 'public');
        $event->image = "/storage/$imagePath";
    } elseif ($request->filled('image')) {
        $event->image = $request->image;
    }

    $event->save();

    return redirect()->route('events.page')->with('success', 'Event updated successfully.');
}


public function delete($id)
{
    $event = Event::findOrFail($id);

    // Check if the logged-in user is the creator or an admin
    if (!auth()->user()->hasRole('admin') && $event->user_id !== auth()->id()) {
        return redirect()->route('events.page')->with('error', 'Unauthorized action.');
    }

    $event->delete();
    return redirect()->route('events.page')->with('success', 'Event deleted successfully.');
}


    public function showPendingEvents()
    {
        // Fetch pending events for the admin to approve or reject
        $pendingEvents = Event::where('status', 'pending')->get();
        return view('pendings-folder.pending-events', compact('pendingEvents'));
    }

    public function approveEvent($id)
{
    $event = Event::findOrFail($id);
    $event->status = 'approved';
    $event->save();

        Notification::create([
        'event_id' => $event->id,
        'status' => 'approved',
    ]);

    // Create a notification for the user who created the event
    if ($event->user_id) {
        Notification::create([
            'title' => 'Event Approved',
            'description' => 'Your event has been approved by the admin.',
            'dateTime' => now(),
            'user_id' => $event->user_id, // The user who created the event
            'link' => route('events.show', ['id' => $event->id]), // Link directly to the specific event
        ]);
    } else {
        Log::error("Approval notification failed: User ID is missing for event ID {$id}");
    }

    return redirect()->route('pending.events')->with('success', 'Event approved successfully.');
}

public function rejectEvent($id)
{
    $event = Event::findOrFail($id);
    $event->status = 'rejected';
    $event->save();

    // Create a notification for the user who created the event
    if ($event->user_id) {
        Notification::create([
            'title' => 'Event Rejected',
            'description' => 'Your event has been rejected by the admin.',
            'dateTime' => now(),
            'user_id' => $event->user_id, // The user who created the event
            'link' => route('events.page'), // Link to events page
        ]);
    } else {
        Log::error("Rejection notification failed: User ID is missing for event ID {$id}");
    }

    return redirect()->route('pending.events')->with('success', 'Event rejected successfully.');
}

}
