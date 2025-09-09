<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Permission;

class AuthorizationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Define gates for all permissions dynamically
        try {
            $permissions = Permission::all();
            
            foreach ($permissions as $permission) {
                Gate::define($permission->name, function ($user) use ($permission) {
                    return $user->hasPermission($permission->name);
                });
            }
        } catch (\Exception $e) {
            // Handle case where permissions table doesn't exist yet
            // This can happen during migrations
        }

        // Define role-based gates
        Gate::define('admin', function ($user) {
            return $user->role && $user->role->name === 'Admin';
        });

        Gate::define('it-staff', function ($user) {
            return $user->role && $user->role->name === 'IT Staff';
        });

        Gate::define('employee', function ($user) {
            return $user->role && $user->role->name === 'Employee';
        });
    }
}
