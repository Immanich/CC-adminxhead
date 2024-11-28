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

    public function index(){
        $offices = Office::all();
        $feedbacks = Feedback::with(['office', 'service'])->get();

        return view('feedbacks.index', compact( 'feedbacks', 'offices'));
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
    Log::info("Fetching reply for feedback ID: $feedbackId"); // Debugging

    $feedback = Feedback::find($feedbackId);

    if (!$feedback) {
        Log::warning("No feedback found for ID: $feedbackId"); // Additional debugging
        return response()->json(['message' => 'Feedback not found'], 404);
    }

    Log::info("Reply found for feedback ID: $feedbackId"); // Success log
    return response()->json([
        'feedback_id' => $feedback->id,
        'feedback' => $feedback->feedback,
        'reply' => $feedback->reply,
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

}

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