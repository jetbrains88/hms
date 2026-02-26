<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        $user = Auth::user();
        
        // If no specific roles required, just check authentication
        if (empty($roles)) {
            return $next($request);
        }

        // Check if user has any of the required roles
        $hasRole = false;
        
        // Super admins can bypass role checks
        if ($user->hasRole('super_admin')) {
            $hasRole = true;
        } elseif ($request->user()->hasAnyRole($roles)) {
            $hasRole = true;
        }
        if (!$hasRole) {
            // Log unauthorized access attempt
            Log::warning('Unauthorized role access attempt', [
                'user_id' => $user->id,
                'user_role' => $user->role ? $user->role->name : 'none',
                'required_roles' => $roles,
                'path' => $request->path()
            ]);

            abort(403, 'Unauthorized: You do not have the required role to access this page.');
        }

        return $next($request);
    }
}