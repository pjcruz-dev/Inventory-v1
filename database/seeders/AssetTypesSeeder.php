<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AssetType;
use Illuminate\Support\Facades\DB;

class AssetTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('asset_types')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $types = [
            ['name' => 'Desktop Computer', 'description' => 'Desktop workstation computers'],
            ['name' => 'Laptop Computer', 'description' => 'Portable laptop computers'],
            ['name' => 'Server', 'description' => 'Server hardware'],
            ['name' => 'Network Switch', 'description' => 'Network switching equipment'],
            ['name' => 'Router', 'description' => 'Network routing equipment'],
            ['name' => 'Printer', 'description' => 'Printing devices'],
            ['name' => 'Scanner', 'description' => 'Scanning devices'],
            ['name' => 'Smartphone', 'description' => 'Mobile phone devices'],
            ['name' => 'Tablet', 'description' => 'Tablet computing devices'],
            ['name' => 'Monitor', 'description' => 'Display monitors']
        ];

        foreach ($types as $type) {
            AssetType::create([
                'name' => $type['name'],
                'description' => $type['description'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}