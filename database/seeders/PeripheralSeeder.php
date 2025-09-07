<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Peripheral;
use Illuminate\Database\Seeder;

class PeripheralSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get laptop assets for reference
        $laptops = Asset::whereHas('assetType', function($query) {
            $query->where('name', 'Laptop');
        })->get();
        
        if ($laptops->isEmpty()) {
            $this->command->info('No laptop assets found. Please run AssetSeeder first.');
            return;
        }
        
        // Create sample peripherals for laptops
        $peripherals = [
            // Peripherals for first laptop
            [
                'asset_id' => $laptops->first()->id,
                'type' => 'Mouse',
                'details' => 'Wireless Bluetooth Mouse',
                'serial_no' => 'MS' . rand(10000, 99999),
            ],
            [
                'asset_id' => $laptops->first()->id,
                'type' => 'Keyboard',
                'details' => 'Mechanical Keyboard with Numeric Keypad',
                'serial_no' => 'KB' . rand(10000, 99999),
            ],
            [
                'asset_id' => $laptops->first()->id,
                'type' => 'Charger',
                'details' => '65W USB-C Power Adapter',
                'serial_no' => 'CH' . rand(10000, 99999),
            ],
            
            // Peripherals for second laptop if available
            [
                'asset_id' => $laptops->skip(1)->first() ? $laptops->skip(1)->first()->id : $laptops->first()->id,
                'type' => 'Docking Station',
                'details' => 'USB-C Docking Station with Dual Monitor Support',
                'serial_no' => 'DS' . rand(10000, 99999),
            ],
            [
                'asset_id' => $laptops->skip(1)->first() ? $laptops->skip(1)->first()->id : $laptops->first()->id,
                'type' => 'Mouse',
                'details' => 'Ergonomic Vertical Mouse',
                'serial_no' => 'MS' . rand(10000, 99999),
            ],
            
            // Peripherals for third laptop if available
            [
                'asset_id' => $laptops->skip(2)->first() ? $laptops->skip(2)->first()->id : $laptops->first()->id,
                'type' => 'External Hard Drive',
                'details' => '1TB USB 3.0 Portable Hard Drive',
                'serial_no' => 'HD' . rand(10000, 99999),
            ],
            [
                'asset_id' => $laptops->skip(2)->first() ? $laptops->skip(2)->first()->id : $laptops->first()->id,
                'type' => 'Headset',
                'details' => 'Noise-Cancelling Bluetooth Headset',
                'serial_no' => 'HS' . rand(10000, 99999),
            ],
        ];

        foreach ($peripherals as $peripheral) {
            Peripheral::create($peripheral);
        }
    }
}