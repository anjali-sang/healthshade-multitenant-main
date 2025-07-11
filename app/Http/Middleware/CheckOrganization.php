<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class CheckOrganization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            // Check if user is not admin (role_id != 1) and has no organization
            if (auth()->user()->role_id != '1' && is_null(auth()->user()->organization_id)) {
                // Allow access only to profile-related routes
                if (!$request->routeIs(['profile.*','organization.create','logout'])) {
                    return redirect()->route('organization.create')
                        ->with('error', 'You must update your profile with an organization before accessing other pages.');
                }
            }
        }
        return $next($request);
    }
}
