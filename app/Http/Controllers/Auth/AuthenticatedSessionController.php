<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ProductController;
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

        logger('attempting login');
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();


            // ✅ BLOCK Super Admin and Medical Rep
        if ($user->role_id == 1 || $user->is_medical_rep) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Your account does not have access to this portal.',
            ]);
        }

            // If the user is a superadmin (role_id = 1), allow login regardless of organization status
            // if ($user->role_id == 1) {
            //     $request->session()->regenerate();
            //     return redirect()->intended(route('organization.catalog', absolute: false));
            // }

            // If the user is not a superadmin, check if the organization is active
            if ($user->organization && !$user->organization->is_active) {
                Auth::logout(); // Logout the user immediately
                return redirect()->route('login')->with('warning', 'Your organization is disabled. Please contact support.');
            }

            // Store organization details in the session
            if ($user->organization) {
                session([
                    'currency'     => $user->organization->currency,
                    'timezone'     => $user->organization->timezone,
                    'date_format'  => $user->organization->date_format,
                    'time_format'  => $user->organization->time_format,
                ]);
            }

            // Regenerate session and redirect to dashboard
            $request->session()->regenerate();
            // if ($user->is_medical_rep) {
            //     logger('i am a medical rep');
            //     return redirect()->intended(route('medical_rep.dashboard', absolute: false));
            // }
            return redirect()->intended(route('organization.catalog', absolute: false));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
