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
        $approvedEvents = Event::where('status', 'approved')->get();
        return view('pages.events', compact('approvedEvents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|url',
        ]);

        try {
            $event = new Event();
            $event->title = $request->title;
            $event->description = $request->description;
            $event->image = $request->image;
            $event->user_id = auth()->id();

            // If it's an admin creating the event, mark it as 'approved', else mark it as 'pending'
            if (auth()->user()->hasRole('admin')) {
                $event->status = 'approved';
                $message = 'Event created successfully.';
            } else {
                $event->status = 'pending';
                $message = 'Event created and waiting for approval.';

                // Create a notification for the admin about the new event
                Notification::create([
                    'title' => 'New Event Pending Approval',
                    'description' => 'A user has created a new event, awaiting your approval.',
                    'dateTime' => now(),
                    'user_id' => 1, // Assuming the admin has ID = 1
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

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return response()->json($event);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|url',
        ]);

        $event = Event::findOrFail($id);
        $event->title = $request->title;
        $event->description = $request->description;
        $event->image = $request->image;
        $event->save();

        return redirect()->route('events.page')->with('success', 'Event updated successfully.');
    }

    public function delete($id)
    {
        $event = Event::findOrFail($id);
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
