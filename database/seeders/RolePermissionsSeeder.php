<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get role IDs
        $adminRole = DB::table('roles')->where('name', 'Admin')->first();
        $itStaffRole = DB::table('roles')->where('name', 'IT Staff')->first();
        $employeeRole = DB::table('roles')->where('name', 'Employee')->first();
        
        // Get all permissions
        $permissions = DB::table('permissions')->get();
        
        $rolePermissions = [];
        
        // Admin gets all permissions
        foreach ($permissions as $permission) {
            $rolePermissions[] = [
                'role_id' => $adminRole->id,
                'permission_id' => $permission->id,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        
        // IT Staff permissions (asset management focused)
        $itStaffPermissionNames = [
            'view_assets', 'create_assets', 'edit_assets', 'delete_assets',
            'transfer_assets', 'maintain_assets', 'dispose_assets',
            'view_users', 'create_users', 'edit_users',
            'view_reports', 'export_data', 'import_data',
            'view_audit_logs', 'manage_categories', 'manage_vendors',
            'manage_departments', 'manage_projects'
        ];
        
        foreach ($permissions as $permission) {
            if (in_array($permission->name, $itStaffPermissionNames)) {
                $rolePermissions[] = [
                    'role_id' => $itStaffRole->id,
                    'permission_id' => $permission->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }
        
        // Employee permissions (limited access)
        $employeePermissionNames = [
            'view_assets', 'view_users', 'view_reports'
        ];
        
        foreach ($permissions as $permission) {
            if (in_array($permission->name, $employeePermissionNames)) {
                $rolePermissions[] = [
                    'role_id' => $employeeRole->id,
                    'permission_id' => $permission->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }
        
        DB::table('role_permissions')->insert($rolePermissions);
    }
}
