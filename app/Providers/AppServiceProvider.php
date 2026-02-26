<?php

namespace App\Providers;

use App\Interfaces\AdminRepositoryInterface;
use App\Interfaces\LabReportRepositoryInterface;
use App\Models\Permission;
use App\Repositories\AdminRepository;
use App\Repositories\LabReportRepository;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AdminRepositoryInterface::class, AdminRepository::class);
        $this->app->bind(LabReportRepositoryInterface::class, LabReportRepository::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        // nafees check 
    //     if (config('app.debug')) {
    //     DB::listen(function ($query) {
    //         Log::info('SQL Executed', [
    //             'sql'      => $query->sql,
    //             'bindings' => $query->bindings,
    //             'time_ms'  => $query->time, // milliseconds
    //         ]);
    //     });
    // }

        // Add phone formatting helper
        if (!function_exists('formatPhone')) {
            function formatPhone($phone)
            {
                if (empty($phone)) return '';

                $clean = preg_replace('/[^0-9]/', '', $phone);
                if (strlen($clean) === 11) {
                    return substr($clean, 0, 4) . '-' . substr($clean, 4, 7);
                }
                return $phone;
            }
        }

        // Add CNIC formatting helper
        if (!function_exists('formatCNIC')) {
            function formatCNIC($cnic)
            {
                if (empty($cnic)) return '';

                $clean = preg_replace('/[^0-9]/', '', $cnic);
                if (strlen($clean) === 13) {
                    return substr($clean, 0, 5) . '-' . substr($clean, 5, 7) . '-' . substr($clean, 12, 1);
                }
                return $cnic;
            }
        }

        // ========== PERMISSION DIRECTIVES ==========
        Blade::if('hasPermission', function ($permission) {
            if (!auth()->check()) return false;
            return auth()->user()->hasPermission($permission);
        });

        // ========== GROUP DIRECTIVES ==========
        Blade::if('hasGroup', function ($group) {
            if (!auth()->check()) return false;
            return auth()->user()->hasGroup($group);
        });

        // ========== ROLE DIRECTIVES ==========
        Blade::if('hasRole', function ($role) {
            if (!auth()->check()) return false;
            return auth()->user()->hasRole($role);
        });

        Blade::if('hasAnyRole', function ($roles) {
            if (!auth()->check()) return false;

            $user = auth()->user();
            $roleNames = is_array($roles) ? $roles : explode(',', $roles);
            return $user->hasAnyRole($roleNames);
        });

        Blade::if('hasAllRoles', function ($roles) {
            if (!auth()->check()) return false;

            $user = auth()->user();
            $roleNames = is_array($roles) ? $roles : explode(',', $roles);
            return $user->hasAllRoles($roleNames);
        });

        // ========== ADDITIONAL HELPFUL DIRECTIVES ==========

        // Check if user is active
        Blade::if('active', function () {
            return auth()->check() && auth()->user()->is_active;
        });

        // Check if user is admin (convenience directive)
        Blade::if('admin', function () {
            return auth()->check() && auth()->user()->hasRole('admin');
        });

        // Check if user has any of the given permissions
        Blade::if('hasAnyPermission', function ($permissions) {
            if (!auth()->check()) return false;

            $user = auth()->user();
            $permissionArray = is_array($permissions) ? $permissions : explode(',', $permissions);

            foreach ($permissionArray as $permission) {
                if ($user->hasPermission(trim($permission))) {
                    return true;
                }
            }

            return false;
        });

        // ========== GATE DEFINITIONS ==========
        try {
            // Check if permissions table exists
            if (Schema::hasTable('permissions')) {
                // Define a generic gate for permission checking
                Gate::before(function ($user, $ability) {
                    if ($user->hasPermission($ability)) {
                        return true;
                    }
                });

                // Define specific gates for common permissions
                $permissions = Permission::all();
                foreach ($permissions as $permission) {
                    Gate::define($permission->name, function ($user) use ($permission) {
                        return $user->hasPermission($permission->name);
                    });
                }
            }
        } catch (\Exception $e) {
            // Silently fail during migrations
            Log::info('Permissions table not available yet: ' . $e->getMessage());
        }

        // ========== BLADE COMPONENTS ==========

        // Role badge component directive
        Blade::directive('roleBadge', function ($expression) {
            return "<?php echo \App\Helpers\UserHelper::roleBadge($expression); ?>";
        });

        // Status badge component directive
        Blade::directive('statusBadge', function ($expression) {
            return "<?php echo \App\Helpers\UserHelper::statusBadge($expression); ?>";
        });

        // Format date directive
        Blade::directive('formatDate', function ($expression) {
            return "<?php echo \Carbon\Carbon::parse($expression)->format('M d, Y h:i A'); ?>";
        });

        // Human readable time directive
        Blade::directive('humanTime', function ($expression) {
            return "<?php echo \Carbon\Carbon::parse($expression)->diffForHumans(); ?>";
        });

        // Initialize Alpine.js component directive
        Blade::directive('alpine', function ($expression) {
            return "<?php echo '<div x-data=\"' . $expression . '\">'; ?>";
        });

        Blade::directive('endalpine', function () {
            return "<?php echo '</div>'; ?>";
        });
    }
}
