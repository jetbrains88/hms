<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class EnsureBranchContext
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user) {
            // Check if branch is set in session
            if (!Session::has('current_branch_id')) {
                // Try to get primary branch
                $primaryBranch = $user->branches()->wherePivot('is_primary', true)->first();

                if ($primaryBranch) {
                    Session::put('current_branch_id', $primaryBranch->id);
                    Session::put('current_branch_name', $primaryBranch->name);
                    Session::put('current_branch_type', $primaryBranch->type);
                } elseif ($user->branches()->count() > 0) {
                    // Use first available branch
                    $firstBranch = $user->branches()->first();
                    Session::put('current_branch_id', $firstBranch->id);
                    Session::put('current_branch_name', $firstBranch->name);
                    Session::put('current_branch_type', $firstBranch->type);
                }
            }

            // Share branch info with all views
            view()->share('currentBranch', (object) [
                'id' => Session::get('current_branch_id'),
                'name' => Session::get('current_branch_name'),
                'type' => Session::get('current_branch_type'),
            ]);
        }

        return $next($request);
    }
}
