<?php

namespace App\Providers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
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
        // Use the View composer only if a user is authenticated
        View::composer('*', function ($view) {
            if (Auth::check()) { // Use Auth facade to safely check authentication
                $user = Auth::user(); // Get the authenticated user

                $unreadCount = 0;

                // Handle logic based on roles
                if ($user->hasRole('head')) {
                    // Normal user: Fetch unread notifications specific to the user
                    $unreadCount = Notification::where('user_id', $user->id)
                        ->where('is_read', false)
                        ->count();
                } elseif ($user->hasRole('admin')) {
                    // Admin: Fetch unread notifications meant for admin
                    $unreadCount = Notification::where('user_id', $user->id)
                        ->where('is_read', false)
                        ->count();
                }

                elseif ($user->hasRole('sub_head')) {
                    // Sub-user: Fetch notifications specific to sub_user
                    $unreadCount = Notification::where('user_id', $user->id)
                        ->where('is_read', false)
                        ->count();
                }

                // Share globally
                $view->with('unreadCount', $unreadCount);
            }
        });
    }
}
