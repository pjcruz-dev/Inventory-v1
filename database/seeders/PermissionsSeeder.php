<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Asset Management
            ['name' => 'view_assets', 'description' => 'View asset listings and details', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'create_assets', 'description' => 'Create new assets', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'edit_assets', 'description' => 'Edit existing assets', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'delete_assets', 'description' => 'Delete assets', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'transfer_assets', 'description' => 'Transfer assets between users/locations', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'maintain_assets', 'description' => 'Manage asset maintenance', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'dispose_assets', 'description' => 'Dispose of assets', 'created_at' => now(), 'updated_at' => now()],
            
            // User Management
            ['name' => 'view_users', 'description' => 'View user listings and profiles', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'create_users', 'description' => 'Create new users', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'edit_users', 'description' => 'Edit user information', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'delete_users', 'description' => 'Delete users', 'created_at' => now(), 'updated_at' => now()],
            
            // Role & Permission Management
            ['name' => 'manage_roles', 'description' => 'Manage user roles', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'manage_permissions', 'description' => 'Manage system permissions', 'created_at' => now(), 'updated_at' => now()],
            
            // Reports & Analytics
            ['name' => 'view_reports', 'description' => 'View system reports', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'export_data', 'description' => 'Export system data', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'import_data', 'description' => 'Import system data', 'created_at' => now(), 'updated_at' => now()],
            
            // System Administration
            ['name' => 'view_audit_logs', 'description' => 'View system audit logs', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'manage_settings', 'description' => 'Manage system settings', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'backup_system', 'description' => 'Perform system backups', 'created_at' => now(), 'updated_at' => now()],
            
            // Department & Project Management
            ['name' => 'manage_departments', 'description' => 'Manage departments', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'manage_projects', 'description' => 'Manage projects', 'created_at' => now(), 'updated_at' => now()],
            
            // Asset Categories & Vendors
            ['name' => 'manage_categories', 'description' => 'Manage asset categories', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'manage_vendors', 'description' => 'Manage vendors', 'created_at' => now(), 'updated_at' => now()]
        ];

        DB::table('permissions')->insert($permissions);
    }
}
