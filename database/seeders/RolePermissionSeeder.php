<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User permissions
            'view-users',
            'create-user',
            'edit-user',
            'delete-user',
            'manage-users',
            
            // Role permissions
            'view-roles',
            'create-role',
            'edit-role',
            'delete-role',
            
            // Permission permissions
            'view-permissions',
            'create-permission',
            'edit-permission',
            'delete-permission',
            
            // Asset Type permissions
            'view-asset-type',
            'create-asset-type',
            'edit-asset-type',
            'delete-asset-type',
            
            // Asset permissions
            'view-assets',
            'create-asset',
            'edit-asset',
            'delete-asset',
            'print-asset',
            'import-assets',
            'export-assets',
            
            // Peripheral permissions
            'view-peripherals',
            'create-peripheral',
            'edit-peripheral',
            'delete-peripheral',
            
            // Asset Transfer permissions
            'view-asset-transfers',
            'create-asset-transfer',
            'edit-asset-transfer',
            'delete-asset-transfer',
            'complete-asset-transfer',
            
            // Print Log permissions
            'view-print-logs',
            'create-print-log',
            'edit-print-log',
            'delete-print-log',
            
            // Audit Trail permissions
            'view-audit-trail',
            
            // Legacy inventory system permissions
            'view-products',
            'create-product',
            'edit-product',
            'delete-product',
            
            'view-categories',
            'create-category',
            'edit-category',
            'delete-category',
            
            'view-suppliers',
            'create-supplier',
            'edit-supplier',
            'delete-supplier',
            
            'view-orders',
            'create-order',
            'edit-order',
            'delete-order',
            
            'view-reports',
            'generate-report',
        ];

        // Create permissions if they don't exist
        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }
        
        $this->command->info('Created permissions.');

        // Create roles and assign permissions
        $adminRole = Role::findOrCreate('Admin', 'web');
        $adminRole->syncPermissions(Permission::all());

        $managerRole = Role::findOrCreate('Manager', 'web');
        $managerRole->syncPermissions([
            'view-users',
            'view-roles',
            'view-permissions',
            
            // Asset Management permissions for Manager
            'view-asset-type',
            'create-asset-type',
            'edit-asset-type',
            'view-assets',
            'create-asset',
            'edit-asset',
            'delete-asset',
            'print-asset',
            'import-assets',
            'export-assets',
            'view-peripherals',
            'create-peripheral',
            'edit-peripheral',
            'delete-peripheral',
            'view-asset-transfers',
            'create-asset-transfer',
            'edit-asset-transfer',
            'complete-asset-transfer',
            'view-print-logs',
            'create-print-log',
            'view-audit-trail',
            
            // Legacy inventory permissions
            'view-products',
            'create-product',
            'edit-product',
            'delete-product',
            'view-categories',
            'create-category',
            'edit-category',
            'delete-category',
            'view-suppliers',
            'create-supplier',
            'edit-supplier',
            'delete-supplier',
            'view-orders',
            'create-order',
            'edit-order',
            'delete-order',
            'view-reports',
            'generate-report',
        ]);

        $staffRole = Role::findOrCreate('Staff', 'web');
        $staffRole->syncPermissions([
            // Asset Management permissions for Staff
            'view-assets',
            'view-peripherals',
            'view-asset-transfers',
            'create-asset-transfer',
            'view-print-logs',
            'print-asset',
            
            // Legacy inventory permissions
            'view-products',
            'view-categories',
            'view-suppliers',
            'view-orders',
            'create-order',
            'edit-order',
            'view-reports',
        ]);

        // Admin role will be assigned in UserSeeder after user creation
    }
}