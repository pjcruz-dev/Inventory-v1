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

        // Create the base assets
        foreach ($assets as $asset) {
            Asset::create($asset);
        }
        
        // Generate additional assets to reach 100 records
        $baseCount = count($assets);
        $remaining = 100 - $baseCount;
        
        $this->command->info("Creating {$remaining} additional assets...");
        
        $models = [
            'Laptop' => ['ThinkPad T480', 'MacBook Air', 'Surface Laptop', 'ZenBook Pro', 'Inspiron 15', 'Chromebook', 'Alienware m15', 'Spectre x360'],
            'Desktop' => ['OptiPlex 7080', 'iMac 27"', 'Surface Studio', 'ThinkCentre', 'Precision Tower', 'ProDesk 600', 'EliteDesk 800'],
            'Monitor' => ['P2419H', 'U2720Q', 'Studio Display', 'ProArt PA278CV', 'Odyssey G7', 'UltraGear 27GN950', 'ViewSonic VP2785'],
            'Printer' => ['LaserJet Pro MFP', 'WorkForce Pro', 'Pixma TR8620', 'EcoTank ET-4760', 'OfficeJet Pro 9015', 'ImageCLASS MF743Cdw'],
            'Server' => ['PowerEdge R640', 'ProLiant DL380', 'ThinkSystem SR650', 'Xserve', 'Supermicro SuperServer', 'PRIMERGY RX2530'],
            'Mobile Device' => ['iPhone 12', 'Galaxy S22', 'Pixel 6', 'iPad Pro', 'Surface Pro', 'Galaxy Tab S7', 'OnePlus 9 Pro'],
            'Networking' => ['Catalyst 9300', 'PowerSwitch S4148', 'UniFi Switch Pro', 'Nexus 9300', 'FortiGate 100F', 'EdgeRouter 4'],
            'Accessories' => ['Magic Keyboard', 'MX Master 3', 'Thunderbolt Dock', 'USB-C Hub', 'Wireless Headset', 'Webcam Pro']
        ];
        
        $manufacturers = ['Dell', 'HP', 'Apple', 'Lenovo', 'Microsoft', 'Samsung', 'Asus', 'Acer', 'LG', 'Cisco', 'Logitech', 'Canon', 'Epson'];
        $locations = ['Main Office', 'IT Storage', 'Finance Department', 'HR Department', 'Marketing', 'Engineering', 'Executive Suite', 'Remote Office', 'Server Room'];
        $statuses = ['available', 'assigned', 'in_repair', 'disposed', 'reserved'];
        
        for ($i = 0; $i < $remaining; $i++) {
            // Select a random asset type
            $assetType = $assetTypes->random();
            $typeName = $assetType->name;
            
            // Get models for this type or use generic if not defined
            $typeModels = $models[$typeName] ?? ['Standard Model', 'Pro Model', 'Enterprise Model', 'Basic Model'];
            
            // Generate a unique asset tag
            $prefix = strtoupper(substr($typeName, 0, 3));
            $assetTag = $prefix . '-' . str_pad($baseCount + $i + 1, 3, '0', STR_PAD_LEFT);
            
            // Determine if asset will be assigned
            $status = $statuses[array_rand($statuses)];
            $assignedToUserId = ($status === 'assigned') ? $users->random()->id : null;
            
            Asset::create([
                'asset_tag' => $assetTag,
                'serial_no' => 'SN' . rand(100000, 999999),
                'asset_type_id' => $assetType->id,
                'model' => $typeModels[array_rand($typeModels)],
                'manufacturer' => $manufacturers[array_rand($manufacturers)],
                'purchase_date' => Carbon::now()->subMonths(rand(1, 36))->format('Y-m-d'),
                'warranty_until' => Carbon::now()->addMonths(rand(-6, 36))->format('Y-m-d'),
                'cost' => rand(200, 5000),
                'status' => $status,
                'location' => $locations[array_rand($locations)],
                'assigned_to_user_id' => $assignedToUserId,
                'created_by' => $users->first()->id,
            ]);
        }
        
        $this->command->info('Created ' . Asset::count() . ' assets in total.');
    }
}