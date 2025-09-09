<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Admin',
                'description' => 'Full system access with all permissions',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'IT Staff',
                'description' => 'IT department staff with asset management permissions',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Employee',
                'description' => 'Regular employee with limited access',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('roles')->insert($roles);
    }
}
