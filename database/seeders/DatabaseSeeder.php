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
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([

            UserSeeder::class,
            AssetTypeSeeder::class,
            AssetSeeder::class,
            PeripheralSeeder::class,
            AssetTransferSeeder::class,
            PrintLogSeeder::class
        ]);
    }
}
