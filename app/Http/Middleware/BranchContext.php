<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return $next($request);
        }

        // Check if user has branch access
        if ($user->branches()->count() === 0) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'No branch access assigned to your account.');
        }

        // If no branch selected in session, set default
        if (!session()->has('current_branch_id')) {
            // Try to get primary branch - without using pivot timestamps
            $primaryBranch = null;
            foreach ($user->branches as $branch) {
                if ($branch->pivot->is_primary) {
                    $primaryBranch = $branch;
                    break;
                }
            }

            if (!$primaryBranch) {
                $primaryBranch = $user->branches->first();
            }

            if ($primaryBranch) {
                session(['current_branch_id' => $primaryBranch->id]);
                session(['current_branch_name' => $primaryBranch->name]);
                session(['current_branch_type' => $primaryBranch->type]);
            }
        }

        // Check if user has access to current branch
        $currentBranchId = session('current_branch_id');
        if ($currentBranchId) {
            $hasAccess = false;
            foreach ($user->branches as $branch) {
                if ($branch->id == $currentBranchId) {
                    $hasAccess = true;
                    break;
                }
            }

            if (!$hasAccess) {
                // Reset to primary branch
                $primaryBranch = $user->branches->first();

                if ($primaryBranch) {
                    session(['current_branch_id' => $primaryBranch->id]);
                    session(['current_branch_name' => $primaryBranch->name]);
                    session(['current_branch_type' => $primaryBranch->type]);
                } else {
                    Auth::logout();
                    return redirect()->route('login')->with('error', 'Branch access revoked.');
                }
            }
        }

        // Share branch info with all views
        view()->share('currentBranch', [
            'id' => session('current_branch_id'),
            'name' => session('current_branch_name'),
            'type' => session('current_branch_type'),
        ]);

        // Add branch_id to request for easy access in controllers
        $request->merge(['branch_id' => session('current_branch_id')]);

        return $next($request);
    }
}
