<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Office;
use App\Models\Service;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Notifications\NewFeedbackNotification;
use Illuminate\Support\Facades\Log;

class FeedbackController extends Controller
{

    public function store(Request $request){
        Log::info('Incoming request payload:', ['payload' => $request->all()]);

        if (empty($request->office_name) || empty($request->service_name)) {
            return response()->json(['message' => 'Office name or service name missing'], 422);
        }

        try {
            $validatedData = $request->validate([
                'office_name' => 'required|string|exists:offices,office_name',
                'service_name' => 'required|string|exists:services,service_name',
                'feedback' => 'required|string',
                'name' => 'nullable|string',
            ]);

            $office = Office::where('office_name', $validatedData['office_name'])->first();
            $service = Service::where('service_name', $validatedData['service_name'])->first();

            if (!$office || !$service) {
                return response()->json(['message' => 'Invalid office or service name'], 422);
            }

            $feedback = Feedback::create([
                'office_id' => $office->id,
                'service_id' => $service->id,
                'feedback' => $validatedData['feedback'],
                'name' => $validatedData['name'] ?? 'Anonymous',
            ]);

            return response()->json([
                'message' => 'Feedback submitted successfully', 
                'feedback_id' => $feedback->id
            ], 201);
            
        } catch (\Exception $e) {
            Log::error('Error processing feedback:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to submit feedback'], 500);
        }
    }


// public function index(Request $request)
// {
//     $user = auth()->user();

//     // Check if the user has an associated office
//     if (!$user->office) {
//         // Return empty collections instead of arrays
//         return view('feedbacks.index', ['feedbacks' => collect(), 'archivedFeedbacks' => collect()]);
//     }

//     // Fetch the user's office ID
//     $officeId = $user->office->id;

//     // Fetch non-archived feedbacks based on the user's office
//     $feedbacks = Feedback::where('office_id', $officeId)
//                          ->where('archived', false) // Only non-archived feedbacks
//                          ->when($request->sort, 
//                             function ($query) use ($request) {
//                              return $query->orderBy($request->sort, $request->direction);
//                          }, function ($query) {
//                              return $query->orderBy('created_at', 'desc');
//                          })
//                          ->get(); // This returns an Eloquent collection

//     // Fetch archived feedbacks separately
//     $archivedFeedbacks = Feedback::where('office_id', $officeId)
//                                  ->where('archived', true) // Only archived feedbacks
//                                  ->get(); // This also returns an Eloquent collection

//     // Pass both collections to the view
//     return view('feedbacks.index', compact('feedbacks', 'archivedFeedbacks'));
// }

public function index(Request $request)
{
    $user = auth()->user();

    // Check if the user has an associated office
    if (!$user->office) {
        // Return empty collections instead of arrays
        return view('feedbacks.index', ['feedbacks' => collect(), 'archivedFeedbacks' => collect()]);
    }

    // Fetch the user's office ID
    $officeId = $user->office->id;

    // Fetch non-archived feedbacks based on the user's office
    $feedbacks = Feedback::select('feedback.*') // Select feedback columns to avoid ambiguous column issues
                         ->where('office_id', $officeId)
                         ->where('archived', false)
                         ->join('offices', 'feedback.office_id', '=', 'offices.id') // Join offices table for office_name
                         ->when($request->sort, 
                            function ($query) use ($request) {
                                // Handle office_name sorting
                                if ($request->sort === 'office_name') {
                                    return $query->orderBy('offices.office_name', $request->direction ?? 'asc');
                                }
                                // Handle other sortable fields
                                return $query->orderBy($request->sort, $request->direction ?? 'asc');
                         }, function ($query) {
                             // Default sorting by created_at
                             return $query->orderBy('created_at', 'desc');
                         })
                         ->get(); // Returns an Eloquent collection

    // Fetch archived feedbacks separately
    $archivedFeedbacks = Feedback::where('office_id', $officeId)
                                 ->where('archived', true)
                                 ->get();

    // Pass both collections to the view
    return view('feedbacks.index', compact('feedbacks', 'archivedFeedbacks'));
}


public function archive($id)
{
    // Find the feedback by ID
    $feedback = Feedback::findOrFail($id);

    // Mark the feedback as archived
    $feedback->archived = true;
    $feedback->save();

    // Return a JSON response to indicate success
    return redirect()->route('feedbacks.index')->with('status', 'Feedback has been archived.');
    // return response()->json(['status' => 'success', 'message' => 'Feedback has been archived.']);
}



// public function archive($id)
// {
//     // Find the feedback by ID
//     $feedback = Feedback::findOrFail($id);

//     // Mark the feedback as archived
//     $feedback->archived = true;
//     $feedback->save();

//     // Redirect back with success message
//     return redirect()->route('feedbacks.index')->with('status', 'Feedback has been archived.');
// }


public function archived()
{
    // Fetch all archived feedbacks
    $feedbacks = Feedback::where('archived', true)->get();

    return view('feedbacks.archived-feedbacks', compact('feedbacks'));
}


public function restore($id)
{
    // Find the archived feedback
    $feedback = Feedback::findOrFail($id);

    // Mark it as not archived (restore)
    $feedback->archived = false;
    $feedback->save();

    // Redirect back with a success message
    return redirect()->route('feedbacks.archived')->with('status', 'Feedback restored successfully!');
}



    public function getFeedbackData()
{
    $feedbacks = Feedback::with(['office', 'service'])->get()->map(function ($feedback) {
        return [
            'feedback_id' => $feedback->id,  // Include feedback_id
            'name' => $feedback->name,
            'office_name' => $feedback->office->office_name,
            'service_name' => $feedback->service->service_name,
            'feedback' => $feedback->feedback,
            'reply' => $feedback->reply,  // Include reply if available
        ];
    });

    return response()->json($feedbacks);
}


public function getReplies($feedbackId)
{
    Log::info("Fetching reply for feedback ID: $feedbackId");

    // Eager load the office relationship
    $feedback = Feedback::with('office')->find($feedbackId);

    if (!$feedback) {
        Log::warning("No feedback found for ID: $feedbackId");
        return response()->json(['message' => 'Feedback not found'], 404);
    }

    Log::info("Reply found for feedback ID: $feedbackId");

    // Check if the office relationship is loaded and its name
    $office = $feedback->office;
    Log::info("Office ID: ", ['office_id' => $feedback->office_id]);  // Log the office_id
    Log::info("Office Name: ", ['office_name' => $office ? $office->office_name : null]);

    $officeName = $office ? $office->office_name : 'Admin';

    return response()->json([
        'feedback_id' => $feedback->id,
        'feedback' => $feedback->feedback,
        'reply' => $feedback->reply,
        'role' => 'By', // You can modify this based on how you want to determine the role
        'replied_by' => $officeName, // Return the office name
    ], 200);
}

public function reply(Request $request, $feedbackId){
    $request->validate([
        'reply' => 'required|string',
    ]);

    $feedback = Feedback::findOrFail($feedbackId);
    $feedback->reply = $request->input('reply');
    $feedback->save(); 

    return redirect()->route('feedbacks.index')->with('success', 'Reply submitted successfully');
}

public function updateReply(Request $request, $feedbackId)
{
    $validatedData = $request->validate([
        'reply' => 'required|string',
    ]);

    $feedback = Feedback::findOrFail($feedbackId);
    $feedback->reply = $validatedData['reply'];
    $feedback->save();

    return redirect()->route('feedbacks.index')->with('success', 'Reply updated successfully');
}

public function destroy($id){
    try {
        $feedback = Feedback::findOrFail($id);
        $feedback->delete();

        return redirect()->route('feedbacks.index')->with('success', 'Feedback deleted successfully.');
    } catch (\Exception $e) {
        return redirect()->route('feedbacks.index')->with('error', 'Failed to delete feedback.');
    }
}

public function archivedDestroy($id){
    try {
        $feedback = Feedback::findOrFail($id);
        $feedback->delete();

        return redirect()->route('feedbacks.archived-feedbacks')->with('success', 'Feedback deleted successfully.');
    } catch (\Exception $e) {
        return redirect()->route('feedbacks.archived-feedbacks')->with('error', 'Failed to delete feedback.');
    }
}

}





// public function getReplies($feedbackId)
// {
//     Log::info("Fetching reply for feedback ID: $feedbackId"); // Debugging

//     $feedback = Feedback::find($feedbackId);

//     if (!$feedback) {
//         Log::warning("No feedback found for ID: $feedbackId"); // Additional debugging
//         return response()->json(['message' => 'Feedback not found'], 404);
//     }

//     Log::info("Reply found for feedback ID: $feedbackId"); // Success log
//     return response()->json([
//         'feedback_id' => $feedback->id,
//         'feedback' => $feedback->feedback,
//         'reply' => $feedback->reply,
//     ], 200);
// }

    

    // public function updateReply(Request $request, $id){
    //     $request->validate([
    //         'reply' => 'required|string',
    //     ]);

    //     $feedback = Feedback::findOrFail($id);
    //     $feedback->reply = $request->input('reply');
    //     $feedback->save();

    //     // return redirect()->back()->with('success', 'Reply updated successfully.');
    //     return response()->json(['reply' => $feedback->reply, 'id' => $feedback->id]);
    // }