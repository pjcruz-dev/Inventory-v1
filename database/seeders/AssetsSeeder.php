<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asset;
use App\Models\AssetType;
use App\Models\AssetCategory;

use App\Models\Vendor;
use App\Models\User;

use Illuminate\Support\Facades\DB;

class AssetsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('assets')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $desktopType = AssetType::where('name', 'Desktop Computer')->first();
        $laptopType = AssetType::where('name', 'Laptop')->first();
        $serverType = AssetType::where('name', 'Server')->first();
        $printerType = AssetType::where('name', 'Printer')->first();
        $monitorType = AssetType::where('name', 'Monitor')->first();
        $switchType = AssetType::where('name', 'Network Switch')->first();
        $routerType = AssetType::where('name', 'Router')->first();
        $smartphoneType = AssetType::where('name', 'Smartphone')->first();
        $tabletType = AssetType::where('name', 'Tablet')->first();
        $scannerType = AssetType::where('name', 'Scanner')->first();

        $computerCategory = AssetCategory::where('name', 'Computer Hardware')->first();
        $serverCategory = AssetCategory::where('name', 'Server Hardware')->first();
        $printerCategory = AssetCategory::where('name', 'Printing Equipment')->first();
        $monitorCategory = AssetCategory::where('name', 'Display Equipment')->first();
        $networkCategory = AssetCategory::where('name', 'Network Equipment')->first();
        $mobileCategory = AssetCategory::where('name', 'Mobile Devices')->first();
        $tabletCategory = AssetCategory::where('name', 'Tablet Devices')->first();
        $scannerCategory = AssetCategory::where('name', 'Scanning Equipment')->first();

        $vendor1 = Vendor::first();
        $vendor2 = Vendor::skip(1)->first();
        $vendor3 = Vendor::skip(2)->first();

        $user1 = User::where('email', 'john.smith@company.com')->first();
        $user2 = User::where('email', 'sarah.johnson@company.com')->first();
        $user3 = User::where('email', 'michael.brown@company.com')->first();



        $assets = [
            [
                'name' => 'Dell OptiPlex 7090',
                'description' => 'Desktop computer for office work',
                'serial_no' => 'DL7090001',
                'asset_tag' => 'AST-001',
                'asset_type_id' => $desktopType->id ?? 1,
                'category_id' => $computerCategory->id ?? 1,
                'vendor_id' => $vendor1->id ?? 1,
                'purchase_date' => '2023-01-15',
                'cost' => 1200.00,
                'warranty_until' => '2026-01-15',
                'status' => 'active',

                'assigned_to_user_id' => $user1->id ?? null,
                'location' => 'Main Office - Floor 1'
            ],
            [
                'name' => 'HP EliteBook 850',
                'description' => 'Business laptop for mobile work',
                'serial_no' => 'HP850001',
                'asset_tag' => 'AST-002',
                'asset_type_id' => $laptopType->id ?? 2,
                'category_id' => $computerCategory->id ?? 1,
                'vendor_id' => $vendor2->id ?? 2,
                'purchase_date' => '2023-02-20',
                'cost' => 1800.00,
                'warranty_until' => '2026-02-20',
                'status' => 'active',

                'assigned_to_user_id' => $user2->id ?? null,
                'location' => 'Main Office - Floor 2'
            ],
            [
                'name' => 'Dell PowerEdge R750',
                'description' => 'Rack server for data center',
                'serial_no' => 'DLR750001',
                'asset_tag' => 'AST-003',
                'asset_type_id' => $serverType->id ?? 3,
                'category_id' => $serverCategory->id ?? 3,
                'vendor_id' => $vendor1->id ?? 1,
                'purchase_date' => '2023-03-10',
                'cost' => 8500.00,
                'warranty_until' => '2028-03-10',
                'status' => 'active',

                'assigned_to_user_id' => null,
                'location' => 'IT Server Room'
            ],
            [
                'name' => 'HP LaserJet Pro 4301',
                'description' => 'Office laser printer',
                'serial_no' => 'HP4301001',
                'asset_tag' => 'AST-004',
                'asset_type_id' => $printerType->id ?? 6,
                'category_id' => $printerCategory->id ?? 6,
                'vendor_id' => $vendor3->id ?? 3,
                'purchase_date' => '2023-04-05',
                'cost' => 450.00,
                'warranty_until' => '2025-04-05',
                'status' => 'active',

                'assigned_to_user_id' => null,
                'location' => 'Main Office - Floor 1'
            ],
            [
                'name' => 'Dell UltraSharp U2723QE',
                'description' => '27-inch 4K monitor',
                'serial_no' => 'DLU2723001',
                'asset_tag' => 'AST-005',
                'asset_type_id' => $monitorType->id ?? 10,
                'category_id' => $monitorCategory->id ?? 10,
                'vendor_id' => $vendor1->id ?? 1,
                'purchase_date' => '2023-05-12',
                'cost' => 650.00,
                'warranty_until' => '2026-05-12',
                'status' => 'active',
                'condition' => 'excellent',
                'assigned_to_user_id' => $user3->id ?? null,
                'location' => 'Main Office - Floor 2'
            ],
            [
                'name' => 'Cisco Catalyst 2960-X',
                'description' => '24-port network switch',
                'serial_no' => 'CS2960X001',
                'asset_tag' => 'AST-006',
                'asset_type_id' => $switchType->id ?? 4,
                'category_id' => $networkCategory->id ?? 4,
                'vendor_id' => $vendor2->id ?? 2,
                'purchase_date' => '2023-06-18',
                'cost' => 1200.00,
                'warranty_until' => '2028-06-18',
                'status' => 'active',
                'condition' => 'excellent',
                'assigned_to_user_id' => null,
                'location' => 'IT Server Room'
            ],
            [
                'name' => 'Lenovo ThinkPad X1 Carbon',
                'description' => 'Ultrabook for executives',
                'serial_no' => 'LNX1C001',
                'asset_tag' => 'AST-007',
                'asset_type_id' => $laptopType->id ?? 2,
                'category_id' => $computerCategory->id ?? 1,
                'vendor_id' => $vendor3->id ?? 3,
                'purchase_date' => '2023-07-22',
                'cost' => 2200.00,
                'warranty_until' => '2026-07-22',
                'status' => 'active',
                'condition' => 'excellent',
                'assigned_to_user_id' => null,
                'location' => 'Main Office - Floor 1'
            ],
            [
                'name' => 'iPhone 14 Pro',
                'description' => 'Company smartphone',
                'serial_no' => 'APL14P001',
                'asset_tag' => 'AST-008',
                'asset_type_id' => $smartphoneType->id ?? 8,
                'category_id' => $mobileCategory->id ?? 8,
                'vendor_id' => $vendor1->id ?? 1,
                'purchase_date' => '2023-08-30',
                'cost' => 1100.00,
                'warranty_until' => '2024-08-30',
                'status' => 'active',
                'condition' => 'good',
                'assigned_to_user_id' => $user1->id ?? null,
                'location' => 'Mobile/Field Work'
            ],
            [
                'name' => 'Samsung Galaxy Tab S8',
                'description' => 'Tablet for presentations',
                'serial_no' => 'SMGS8001',
                'asset_tag' => 'AST-009',
                'asset_type_id' => $tabletType->id ?? 9,
                'category_id' => $tabletCategory->id ?? 9,
                'vendor_id' => $vendor2->id ?? 2,
                'purchase_date' => '2023-09-15',
                'cost' => 750.00,
                'warranty_until' => '2025-09-15',
                'status' => 'active',
                'condition' => 'good',
                'assigned_to_user_id' => null,
                'location' => 'Main Office - Floor 2'
            ],
            [
                'name' => 'Canon imageFORMULA R40',
                'description' => 'Document scanner',
                'serial_no' => 'CNR40001',
                'asset_tag' => 'AST-010',
                'asset_type_id' => $scannerType->id ?? 7,
                'category_id' => $scannerCategory->id ?? 7,
                'vendor_id' => $vendor3->id ?? 3,
                'purchase_date' => '2023-10-08',
                'cost' => 320.00,
                'warranty_until' => '2025-10-08',
                'status' => 'active',
                'condition' => 'excellent',
                'assigned_to_user_id' => null,
                'location' => 'Main Office - Floor 1'
            ]
        ];

        foreach ($assets as $asset) {
            Asset::create([
                'name' => $asset['name'],
                'description' => $asset['description'],
                'serial_no' => $asset['serial_no'],
                'asset_tag' => $asset['asset_tag'],
                'asset_type_id' => $asset['asset_type_id'],
                'category_id' => $asset['category_id'],
                'vendor_id' => $asset['vendor_id'],
                'purchase_date' => $asset['purchase_date'],
                'cost' => $asset['cost'],
                'warranty_until' => $asset['warranty_until'],
                'status' => $asset['status'],

                'assigned_to_user_id' => $asset['assigned_to_user_id'],
                'location' => $asset['location'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}