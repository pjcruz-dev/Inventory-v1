<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

class CheckUserPermissions extends Command
{
    protected $signature = 'user:check-permissions {email?}';
    protected $description = 'Check user permissions and assign roles if needed';

    public function handle()
    {
        $email = $this->argument('email') ?? 'admin@inventory.test';
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found.");
            return 1;
        }
        
        $this->info("User: {$user->name} ({$user->email})");
        $this->info("Roles: " . $user->roles->pluck('name')->join(', '));
        
        // Check if user has import/export permissions
        $importPermission = $user->can('import-assets');
        $exportPermission = $user->can('export-assets');
        
        $this->info("Import Assets Permission: " . ($importPermission ? 'Yes' : 'No'));
        $this->info("Export Assets Permission: " . ($exportPermission ? 'Yes' : 'No'));
        
        // If user doesn't have Admin role, assign it
        if (!$user->hasRole('Admin')) {
            $adminRole = Role::where('name', 'Admin')->first();
            if ($adminRole) {
                $user->assignRole('Admin');
                $this->info("Assigned Admin role to user.");
            } else {
                $this->error("Admin role not found. Please run RolePermissionSeeder first.");
            }
        }
        
        return 0;
    }
}