<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\MunicipalOfficialController;
use App\Http\Controllers\MvmspController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServicesInfoController;
use Illuminate\Support\Facades\Route;

// Login route
Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('login');

// Dashboard route (after login)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes that require authentication
Route::middleware('auth')->group(function () {

    // Profile management routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Show the MVMSP page
    Route::get('/mvmsp', [MvmspController::class, 'show'])->name('mvmsp');
    Route::post('/mvmsp', [MvmspController::class, 'store'])->name('mvmsp.store');
    Route::get('/mvmsp/{id}/edit', [MvmspController::class, 'edit'])->name('mvmsp.edit');
    Route::put('/mvmsp/{id}/update', [MvmspController::class, 'update'])->name('mvmsp.update');
    Route::delete('/mvmsp/{id}', [MvmspController::class, 'destroy'])->name('mvmsp.delete');

    Route::get('/municipal-officials', [MunicipalOfficialController::class, 'index'])->name('municipal-officials');
    Route::get('/municipal-officials/{id}/edit', [MunicipalOfficialController::class, 'edit'])->name('municipal-officials.edit');
    Route::put('/municipal-officials/{id}', [MunicipalOfficialController::class, 'update'])->name('municipal-officials.update');
    Route::get('/year/edit', [MunicipalOfficialController::class, 'editYear']);
    Route::post('/year/update', [MunicipalOfficialController::class, 'updateYear'])->name('updateYear');


    Route::view('/pages/org-chart', 'pages.org-chart')->name('org-chart');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::get('/notifications/fetch', [NotificationController::class, 'fetchNotifications'])->name('notifications.fetch');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');


    // admin/users/sub_users
    Route::middleware('role:admin|user|sub_user')->group(function () {

        // Office management
        Route::get('/admin/offices', [OfficeController::class, 'index'])->name('admin.offices.index');
        Route::post('/admin/offices', [OfficeController::class, 'store'])->name('admin.storeOffice');
        Route::put('/admin/offices/{id}', [OfficeController::class, 'update'])->name('admin.updateOffice');
        Route::delete('/admin/offices/{id}', [OfficeController::class, 'destroy'])->name('admin.deleteOffice');

        Route::get('/events/archived', [EventController::class, 'archived'])->name('events.archived');
        Route::get('/events/{id}/show-archived', [EventController::class, 'showExpiredEvent'])->name('events.showExpiredEvent');


        // Service management (accessible to both roles)
        Route::post('/admin/services/{officeId}/store', [ServiceController::class, 'storeService'])->name('admin.storeService');
        Route::get('/admin/services/{id}/edit', [ServiceController::class, 'edit'])->name('admin.editService');
        Route::put('/admin/services/{serviceId}/update', [ServiceController::class, 'updateService'])->name('admin.updateService');
        Route::delete('/admin/services/{serviceId}', [ServiceController::class, 'deleteService'])->name('admin.deleteService');
    });

    // admin
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/pending-services', [ServiceController::class, 'pendingServices'])->name('pending.services');
        Route::get('/pending-services/{id}', [ServiceController::class, 'showPendingService'])->name('pending.services.show');
        Route::post('/admin/services/{serviceId}/approve', [ServiceController::class, 'approveService'])->name('services.approve');
        Route::post('/admin/services/{serviceId}/reject', [ServiceController::class, 'rejectService'])->name('services.reject');

        Route::get('/pendings', function () {
            return redirect()->route('pending.events');
        })->name('pendings');
        // Pending events (admin approval)
        Route::get('/admin/pending-events', [EventController::class, 'showPendingEvents'])->name('pending.events');
        Route::get('/pending-events/{id}', [EventController::class, 'showPendingEvent'])->name('pending.events.show');
        Route::post('/admin/events/{id}/approve', [EventController::class, 'approveEvent'])->name('events.approve');
        Route::post('/admin/events/{id}/reject', [EventController::class, 'rejectEvent'])->name('events.reject');
    });

    // admin/user
    Route::middleware('role:admin|user')->group(function () {
        Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::post('/admin/users', [UserController::class, 'store'])->name('admin.storeUser');
        Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

        Route::post('/admin/users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('admin.users.toggleStatus');
    });

    // Events management for users
    Route::get('/events', [EventController::class, 'index'])->name('events.page'); // Display approved events
    Route::post('/events', [EventController::class, 'store'])->name('events.store');

    // Office and service management routes
    Route::get('/offices', [OfficeController::class, 'index'])->name('offices');
    Route::get('/offices/{id}', [OfficeController::class, 'showServices'])->name('offices.services');
    Route::get('/offices/{office_id}/services/{service_id}', [OfficeController::class, 'serviceDetails'])->name('services.show');
        // para sa back button
    Route::get('/offices/{id}/services', [OfficeController::class, 'showServices'])->name('offices.showServices');


    // Feedback page
    Route::get('/offices/feedbacks', [OfficeController::class, 'feedbacks'])->name('feedbacks');

    // Service Info management
    Route::get('/services/{id}', [ServicesInfoController::class, 'show'])->name('services.show');
    Route::get('/services/{id}/details', [ServiceController::class, 'showService'])->name('services.details');
    Route::post('/services/store', [ServicesInfoController::class, 'store'])->name('services.store');
    Route::put('/services/{service_id}/info/{info_id}', [ServicesInfoController::class, 'update'])->name('services.info.update');
    Route::delete('/services/{service_id}/info/{info_id}', [ServicesInfoController::class, 'destroy'])->name('services.info.delete');
});

// Feedback routes
Route::get('/feedbacks', [FeedbackController::class, 'index'])->name('feedbacks.index');
Route::post('/feedbacks', [FeedbackController::class, 'store'])->name('feedbacks.store');
Route::post('/feedbacks/{feedback}/reply', [FeedbackController::class, 'reply'])->name('feedbacks.reply');
Route::put('/feedbacks/{id}/update-reply', [FeedbackController::class, 'updateReply'])->name('feedbacks.updateReply');
// web.php
// Route::put('/feedbacks/{id}/reply', [FeedbackController::class, 'updateReply'])->name('feedbacks.updateReply');


    Route::get('/events/{id}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
    Route::put('/events/{id}/update', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{id}', [EventController::class, 'delete'])->name('events.delete');

    // Feedback routes
    Route::get('/feedbacks', [FeedbackController::class, 'index'])->name('feedbacks.index');
    Route::post('/feedbacks', [FeedbackController::class, 'store'])->name('feedbacks.store');
    Route::post('/feedbacks/{feedback}/reply', [FeedbackController::class, 'reply'])->name('feedbacks.reply');
    Route::delete('/feedbacks/{feedback}', [FeedbackController::class, 'destroy'])->name('feedbacks.destroy');


    // Authentication routes
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
