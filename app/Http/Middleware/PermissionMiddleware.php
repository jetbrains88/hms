<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, $permission): Response
    {
        $user = auth()->user();
        
        if (!$user) {
            abort(401);
        }
        
        // Super admins have all permissions
        if ($user->hasRole('super_admin')) {
            return $next($request);
        }
        
        if (!$user->hasPermission($permission)) {
            abort(403, 'You do not have permission to perform this action.');
        }
        
        return $next($request);
    }
}