<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticatedUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        // Check if user is active (you can add an 'is_active' column later)
        $user = Auth::user();
        
        // Optional: Check if user email is verified
        if ($user->email_verified_at === null) {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Please verify your email address before logging in.');
        }

        // Share user data with all views
        view()->share('currentUser', $user);
        view()->share('userRole', $user->role);

        return $next($request);
    }
}