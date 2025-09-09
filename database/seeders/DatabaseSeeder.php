<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
            PermissionsSeeder::class,
            RolePermissionsSeeder::class,
            AdminUserSeeder::class,
            AssetCategoriesSeeder::class,
            AssetTypesSeeder::class,
            ManufacturersSeeder::class,
            VendorsSeeder::class,
            DepartmentsSeeder::class,
            ProjectsSeeder::class,
            UsersSeeder::class,
            AssetsSeeder::class,
            PeripheralsSeeder::class,
        ]);

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
