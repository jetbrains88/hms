<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permission
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Super admin bypass
        if ($user->hasRole('super_admin')) {
            return $next($request);
        }

        // Check if user has the required permission
        if (!$user->hasPermission($permission)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Missing permission: ' . $permission
                ], 403);
            }

            return redirect()->back()->with('error', 'You do not have permission to perform this action.');
        }

        return $next($request);
    }
}
