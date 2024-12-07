<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Attempt to authenticate the user
        $request->authenticate();

        // Check if the user is disabled
        $user = Auth::user();
        if ($user->is_disabled) {
            // Log out the user if the account is disabled
            Auth::logout();

            // Redirect with an error message
            return redirect()->route('login')->withErrors([
                'username' => 'Your account has been disabled. Please contact the admin.',
            ]);
        }

        // Regenerate the session and redirect to the intended page
        $request->session()->regenerate();

        $request->session()->forget('url.intended');

        // Redirect to the MVMPS page or the intended destination
        return redirect()->intended(route('mvmsp'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
