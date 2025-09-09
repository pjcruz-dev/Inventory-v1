<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AssetCategory;
use Illuminate\Support\Facades\DB;

class AssetCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('asset_categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $categories = [
            ['name' => 'Computer Hardware', 'description' => 'Desktop computers, laptops, servers'],
            ['name' => 'Network Equipment', 'description' => 'Routers, switches, access points'],
            ['name' => 'Office Equipment', 'description' => 'Printers, scanners, copiers'],
            ['name' => 'Mobile Devices', 'description' => 'Smartphones, tablets, mobile accessories'],
            ['name' => 'Audio Visual', 'description' => 'Monitors, projectors, speakers'],
            ['name' => 'Furniture', 'description' => 'Desks, chairs, cabinets'],
            ['name' => 'Software Licenses', 'description' => 'Operating systems, applications, subscriptions'],
            ['name' => 'Security Equipment', 'description' => 'Cameras, access control systems'],
            ['name' => 'Storage Devices', 'description' => 'External drives, NAS, backup systems'],
            ['name' => 'Telecommunications', 'description' => 'Phones, headsets, communication devices']
        ];

        foreach ($categories as $category) {
            AssetCategory::create([
                'name' => $category['name'],
                'description' => $category['description'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}