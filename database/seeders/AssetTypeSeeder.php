<?php

namespace Database\Seeders;

use App\Models\AssetType;
use Illuminate\Database\Seeder;

class AssetTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $assetTypes = [
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

        foreach ($assetTypes as $assetType) {
            AssetType::create($assetType);
        }
    }
}