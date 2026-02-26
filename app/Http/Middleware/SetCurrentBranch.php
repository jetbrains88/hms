<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCurrentBranch
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        if ($user) {
            $branchId = session('current_branch_id');
            
            // If no branch in session, try to get primary branch
            if (!$branchId) {
                $primaryBranch = $user->primaryBranch();
                if ($primaryBranch) {
                    session(['current_branch_id' => $primaryBranch->id]);
                }
            }
        }
        
        return $next($request);
    }
}