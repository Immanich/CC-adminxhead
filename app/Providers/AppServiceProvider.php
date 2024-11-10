<?php

namespace App\Providers;

use App\Models\Notification;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (auth()->check()) {
            $user = auth()->user();

            // If the user is a normal user, get only their unread notifications
            if ($user->hasRole('user')) {
                $unreadCount = Notification::where('user_id', $user->id)->where('is_read', false)->count();
            }

            // If the user is an admin, get unread notifications for users' actions
            if ($user->hasRole('admin')) {
                $unreadCount = Notification::where('is_read', false)->count(); // You can modify this if needed
            }

            // Share the count globally
            View::share('unreadCount', $unreadCount ?? 0);
        }
    }
}
