<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\AssetType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get asset types and users for reference
        $assetTypes = AssetType::all();
        $users = User::all();
        
        if ($assetTypes->isEmpty()) {
            $this->command->info('No asset types found. Please run AssetTypeSeeder first.');
            return;
        }
        
        if ($users->isEmpty()) {
            $this->command->info('No users found. Please run UserSeeder first.');
            return;
        }
        
        // Create sample assets
        $assets = [
            // Laptops
            [
                'asset_tag' => 'LAP-001',
                'serial_no' => 'SN' . rand(100000, 999999),
                'asset_type_id' => $assetTypes->where('name', 'Laptop')->first()->id,
                'model' => 'ThinkPad X1 Carbon',
                'manufacturer' => 'Lenovo',
                'purchase_date' => Carbon::now()->subMonths(rand(1, 24))->format('Y-m-d'),
                'warranty_until' => Carbon::now()->addYears(rand(1, 3))->format('Y-m-d'),
                'cost' => rand(1000, 2500),
                'status' => 'assigned',
                'location' => 'Main Office',
                'assigned_to_user_id' => $users->random()->id,
                'created_by' => $users->first()->id,
            ],
            [
                'asset_tag' => 'LAP-002',
                'serial_no' => 'SN' . rand(100000, 999999),
                'asset_type_id' => $assetTypes->where('name', 'Laptop')->first()->id,
                'model' => 'MacBook Pro 16"',
                'manufacturer' => 'Apple',
                'purchase_date' => Carbon::now()->subMonths(rand(1, 24))->format('Y-m-d'),
                'warranty_until' => Carbon::now()->addYears(rand(1, 3))->format('Y-m-d'),
                'cost' => rand(2000, 3500),
                'status' => 'assigned',
                'location' => 'Main Office',
                'assigned_to_user_id' => $users->random()->id,
                'created_by' => $users->first()->id,
            ],
            [
                'asset_tag' => 'LAP-003',
                'serial_no' => 'SN' . rand(100000, 999999),
                'asset_type_id' => $assetTypes->where('name', 'Laptop')->first()->id,
                'model' => 'XPS 15',
                'manufacturer' => 'Dell',
                'purchase_date' => Carbon::now()->subMonths(rand(1, 24))->format('Y-m-d'),
                'warranty_until' => Carbon::now()->addYears(rand(1, 3))->format('Y-m-d'),
                'cost' => rand(1500, 2800),
                'status' => 'available',
                'location' => 'IT Storage',
                'assigned_to_user_id' => null,
                'created_by' => $users->first()->id,
            ],
            
            // Monitors
            [
                'asset_tag' => 'MON-001',
                'serial_no' => 'SN' . rand(100000, 999999),
                'asset_type_id' => $assetTypes->where('name', 'Monitor')->first()->id,
                'model' => 'UltraSharp 27"',
                'manufacturer' => 'Dell',
                'purchase_date' => Carbon::now()->subMonths(rand(1, 24))->format('Y-m-d'),
                'warranty_until' => Carbon::now()->addYears(rand(1, 3))->format('Y-m-d'),
                'cost' => rand(300, 800),
                'status' => 'assigned',
                'location' => 'Main Office',
                'assigned_to_user_id' => $users->random()->id,
                'created_by' => $users->first()->id,
            ],
            [
                'asset_tag' => 'MON-002',
                'serial_no' => 'SN' . rand(100000, 999999),
                'asset_type_id' => $assetTypes->where('name', 'Monitor')->first()->id,
                'model' => 'ProDisplay XDR',
                'manufacturer' => 'Apple',
                'purchase_date' => Carbon::now()->subMonths(rand(1, 24))->format('Y-m-d'),
                'warranty_until' => Carbon::now()->addYears(rand(1, 3))->format('Y-m-d'),
                'cost' => rand(1500, 5000),
                'status' => 'in_repair',
                'location' => 'IT Repair',
                'assigned_to_user_id' => null,
                'created_by' => $users->first()->id,
            ],
            
            // Printers
            [
                'asset_tag' => 'PRT-001',
                'serial_no' => 'SN' . rand(100000, 999999),
                'asset_type_id' => $assetTypes->where('name', 'Printer')->first()->id,
                'model' => 'LaserJet Pro',
                'manufacturer' => 'HP',
                'purchase_date' => Carbon::now()->subMonths(rand(1, 24))->format('Y-m-d'),
                'warranty_until' => Carbon::now()->addYears(rand(1, 3))->format('Y-m-d'),
                'cost' => rand(200, 600),
                'status' => 'available',
                'location' => 'Finance Department',
                'assigned_to_user_id' => null,
                'created_by' => $users->first()->id,
            ],
            
            // Servers
            [
                'asset_tag' => 'SRV-001',
                'serial_no' => 'SN' . rand(100000, 999999),
                'asset_type_id' => $assetTypes->where('name', 'Server')->first()->id,
                'model' => 'PowerEdge R740',
                'manufacturer' => 'Dell',
                'purchase_date' => Carbon::now()->subMonths(rand(1, 24))->format('Y-m-d'),
                'warranty_until' => Carbon::now()->addYears(rand(1, 3))->format('Y-m-d'),
                'cost' => rand(3000, 8000),
                'status' => 'available',
                'location' => 'Server Room',
                'assigned_to_user_id' => null,
                'created_by' => $users->first()->id,
            ],
            
            // Mobile Devices
            [
                'asset_tag' => 'MOB-001',
                'serial_no' => 'SN' . rand(100000, 999999),
                'asset_type_id' => $assetTypes->where('name', 'Mobile Device')->first()->id,
                'model' => 'iPhone 13 Pro',
                'manufacturer' => 'Apple',
                'purchase_date' => Carbon::now()->subMonths(rand(1, 24))->format('Y-m-d'),
                'warranty_until' => Carbon::now()->addYears(rand(1, 3))->format('Y-m-d'),
                'cost' => rand(800, 1300),
                'status' => 'assigned',
                'location' => 'Main Office',
                'assigned_to_user_id' => $users->random()->id,
                'created_by' => $users->first()->id,
            ],
            [
                'asset_tag' => 'MOB-002',
                'serial_no' => 'SN' . rand(100000, 999999),
                'asset_type_id' => $assetTypes->where('name', 'Mobile Device')->first()->id,
                'model' => 'Galaxy S21',
                'manufacturer' => 'Samsung',
                'purchase_date' => Carbon::now()->subMonths(rand(1, 24))->format('Y-m-d'),
                'warranty_until' => Carbon::now()->addYears(rand(1, 3))->format('Y-m-d'),
                'cost' => rand(700, 1200),
                'status' => 'disposed',
                'location' => 'Disposed',
                'assigned_to_user_id' => null,
                'created_by' => $users->first()->id,
            ],
        ];

        foreach ($assets as $asset) {
            Asset::create($asset);
        }
    }
}