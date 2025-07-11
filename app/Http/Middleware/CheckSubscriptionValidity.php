<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscriptionValidity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Skip check if not logged in or user is admin
        // if (!$user || $user->role_id == 1 || $user->is_medical_rep) {
        //     return $next($request);
        // }
        if ($request->routeIs(['profile.*', 'logout'])) {
            return $next($request);
        }
        if ($user->organization) {
            if (!$user->organization?->subscription_valid || now()->greaterThan($user->organization->subscription_valid)) {
                return redirect()->route('pricing')->with('error', 'You dont have valid subscription plan.');
            }
        }
        return $next($request);
        \Log::info('Org subscription valid date:', [
    'date' => optional($user->organization)->subscription_valid,
    'now' => now()
]);
    }

    
}
