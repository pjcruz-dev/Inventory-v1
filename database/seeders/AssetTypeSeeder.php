<?php

namespace Database\Seeders;

use App\Models\AssetType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AssetTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Base asset types that will always be created
        $baseAssetTypes = [
            [
                'name' => 'Laptop',
                'description' => 'Portable computers for work and productivity',
            ],
            [
                'name' => 'Desktop',
                'description' => 'Stationary computers for office use',
            ],
            [
                'name' => 'Monitor',
                'description' => 'Display screens for computers',
            ],
            [
                'name' => 'Printer',
                'description' => 'Devices for printing documents and materials',
            ],
            [
                'name' => 'Server',
                'description' => 'Computing hardware designed for processing requests and delivering data',
            ],
            [
                'name' => 'Network Equipment',
                'description' => 'Devices that facilitate network connectivity and communication',
            ],
            [
                'name' => 'Mobile Device',
                'description' => 'Smartphones, tablets, and other portable electronic devices',
            ],
            [
                'name' => 'Peripheral',
                'description' => 'External devices that provide input/output functionality',
            ],
        ];
        
        // Create the base asset types
        foreach ($baseAssetTypes as $assetType) {
            AssetType::create($assetType);
        }
        
        // Generate additional asset types to reach 100 records
        $categories = ['IT Equipment', 'Office Equipment', 'Audio/Visual', 'Security', 'Storage', 'Communication', 'Testing', 'Development'];
        $suffixes = ['Pro', 'Ultra', 'Lite', 'Max', 'Mini', 'Plus', 'Advanced', 'Basic', 'Enterprise', 'Standard'];
        
        $count = count($baseAssetTypes);
        $remaining = 100 - $count;
        
        $this->command->info("Creating {$remaining} additional asset types...");
        
        for ($i = 0; $i < $remaining; $i++) {
            $category = $categories[array_rand($categories)];
            $suffix = $suffixes[array_rand($suffixes)];
            $name = $category . ' ' . $suffix . ' ' . Str::random(3);
            
            AssetType::create([
                'name' => $name,
                'description' => 'Auto-generated asset type for ' . $category . ' category',
            ]);
        }
        
        $this->command->info('Created ' . AssetType::count() . ' asset types in total.');
    }
}