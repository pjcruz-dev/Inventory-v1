<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the Admin role
        $adminRole = Role::where('name', 'Admin')->first();
        
        if (!$adminRole) {
            $this->command->error('Admin role not found. Please run RolesSeeder first.');
            return;
        }
        
        // Create admin user if it doesn't exist
        $adminUser = User::where('email', 'admin@gmail.com')->first();
        
        if (!$adminUser) {
            $adminUser = User::create([
                'employee_no' => 'ADMIN001',
                'first_name' => 'System',
                'last_name' => 'Administrator',
                'department' => 'IT',
                'position' => 'System Administrator',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('123123123'),
                'role_id' => $adminRole->id,
                'status' => 'Active',
            ]);
            
            $this->command->info('Admin user created successfully.');
        } else {
            // Update existing user with admin role
            $adminUser->update([
                'role_id' => $adminRole->id,
                'password' => Hash::make('123123123'),
                'status' => 'Active',
            ]);
            
            $this->command->info('Admin user updated successfully.');
        }
    }
}
