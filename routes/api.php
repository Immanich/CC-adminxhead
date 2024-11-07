<?php

use App\Http\Controllers\API\GuestController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\ServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/offices', [GuestController::class, 'getOffices']);
Route::get('/offices/{office_id}/services', [GuestController::class, 'getServicesForOffice']);
Route::get('/offices/{office_id}/services/{service_id}', [GuestController::class, 'getServiceInfo']);
Route::get('/events', [GuestController::class, 'getEvents']);
Route::get('/events/{event_id}', [GuestController::class, 'getEventById']);
Route::get('/notifications', [NotificationController::class, 'getNotifications']);


Route::options('/feedback', function (Request $request) {
    return response()->json(['status' => 'OK'], 200);
});
// Define routes for feedback submission, fetching, and replying
Route::middleware(['cors'])->post('/feedback', [FeedbackController::class, 'store']);
Route::middleware(['cors'])->get('/feedback', [FeedbackController::class, 'getFeedbackData']);

Route::middleware(['cors'])->get('/feedback/{feedbackId}/replies', [FeedbackController::class, 'getReplies']);
Route::middleware(['cors'])->post('/feedback/{feedbackId}/reply', [FeedbackController::class, 'reply']);
Route::middleware(['cors'])->put('/feedback/{feedbackId}/reply', [FeedbackController::class, 'updateReply']);



// Route::middleware(['cors'])->post('/feedback', [FeedbackController::class, 'store']);
// Route::middleware('auth:sanctum')->group(function () {
//     // Route::get('auth/user', [OfficeController::class, 'user']);
//     Route::get('user/offices', [OfficeController::class, 'index']);
//     // Route::
// });

// Route::get
// Route::post('auth/token', [TokenController::class, 'store']);
// Route::post('user/register', [RegisterController::class, 'store']);