<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Peripheral;
use App\Models\Asset;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PeripheralsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('peripherals')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $asset1 = Asset::where('asset_tag', 'AST-001')->first();
        $asset2 = Asset::where('asset_tag', 'AST-002')->first();
        $asset3 = Asset::where('asset_tag', 'AST-005')->first();
        $asset4 = Asset::where('asset_tag', 'AST-007')->first();

        $user1 = User::where('email', 'john.smith@company.com')->first();
        $user2 = User::where('email', 'sarah.johnson@company.com')->first();
        $user3 = User::where('email', 'michael.brown@company.com')->first();
        $user4 = User::where('email', 'emily.davis@company.com')->first();

        $peripherals = [
            [
                'type' => 'Mouse',
                'details' => 'Logitech MX Master 3 - Wireless mouse with precision tracking',
                'serial_no' => 'LG-MX3-001',
                'asset_id' => $asset1->id ?? null
            ],
            [
                'type' => 'Keyboard',
                'details' => 'Dell KB216 - Standard USB keyboard',
                'serial_no' => 'DL-KB216-001',
                'asset_id' => $asset1->id ?? null
            ],
            [
                'type' => 'Docking Station',
                'details' => 'HP USB-C Dock G5 - Multi-port docking station',
                'serial_no' => 'HP-DOCK-001',
                'asset_id' => $asset2->id ?? null
            ],
            [
                'type' => 'Webcam',
                'details' => 'Logitech C920 - HD webcam for video conferencing',
                'serial_no' => 'LG-C920-001',
                'asset_id' => $asset3->id ?? null
            ],
            [
                'type' => 'Headset',
                'details' => 'Jabra Evolve 75 - Wireless noise-canceling headset',
                'serial_no' => 'JB-EV75-001',
                'asset_id' => null
            ],
            [
                'type' => 'Mouse',
                'details' => 'Microsoft Wireless Mobile Mouse 1850 - Compact wireless mouse',
                'serial_no' => 'MS-WM1850-001',
                'asset_id' => $asset4->id ?? null
            ],
            [
                'type' => 'USB Hub',
                'details' => 'Anker PowerExpand+ 7-in-1 - Multi-port USB hub',
                'serial_no' => 'ANK-HUB-001',
                'asset_id' => null
            ],
            [
                'type' => 'Keyboard',
                'details' => 'Logitech MX Keys - Wireless illuminated keyboard',
                'serial_no' => 'LG-MXKEYS-001',
                'asset_id' => null
            ],
            [
                'type' => 'Charger',
                'details' => 'Belkin BOOST↑CHARGE™ 15W - Wireless charging pad',
                'serial_no' => 'BLK-WC15-001',
                'asset_id' => null
            ],
            [
                'type' => 'Mousepad',
                'details' => 'SteelSeries QcK Large - Gaming mousepad',
                'serial_no' => 'SS-QCK-001',
                'asset_id' => null
            ]
        ];

        foreach ($peripherals as $peripheral) {
            Peripheral::create([
                'type' => $peripheral['type'],
                'details' => $peripheral['details'],
                'serial_no' => $peripheral['serial_no'],
                'asset_id' => $peripheral['asset_id'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}