<?php

namespace App\Http\Controllers;

use App\Models\Event;
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

            if (auth()->user()->hasRole('admin')) {
                $event->status = 'approved';
                $message = 'Event created successfully.';
            } else {
                $event->status = 'pending';
                $message = 'Event created and waiting for approval.';
            }

            $event->save();

            return redirect()->route('events.page')->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create event.');
        }
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
        // Approve the event
        $event = Event::findOrFail($id);
        $event->status = 'approved';
        $event->save();

        return redirect()->route('pending.events')->with('success', 'Event approved successfully.');
    }

    public function rejectEvent($id)
    {
        // Reject the event
        $event = Event::findOrFail($id);
        $event->status = 'rejected';
        $event->save();

        return redirect()->route('pending.events')->with('success', 'Event rejected successfully.');
    }
}
