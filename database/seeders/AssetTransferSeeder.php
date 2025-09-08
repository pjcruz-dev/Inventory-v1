<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\AssetTransfer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AssetTransferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get assigned assets and users for reference
        $assignedAssets = Asset::where('status', 'assigned')->get();
        $users = User::all();
        
        if ($assignedAssets->isEmpty()) {
            $this->command->info('No assigned assets found. Please run AssetSeeder first.');
            return;
        }
        
        if ($users->count() < 2) {
            $this->command->info('Not enough users found. Please run UserSeeder first.');
            return;
        }
        
        // Create initial sample asset transfers
        $transferCount = 0;
        $locations = ['IT Department', 'Finance Department', 'HR Department', 'Marketing', 'Engineering', 'Executive Suite', 'Remote Office', 'Branch Office', 'Warehouse', 'Customer Service'];
        $transferReasons = ['Employee reassignment', 'Department restructuring', 'Equipment upgrade', 'Project requirements', 'Office relocation', 'Remote work arrangement', 'New hire onboarding', 'Employee departure', 'Temporary assignment', 'Maintenance needs'];
        $statuses = ['completed', 'pending', 'cancelled', 'in_progress'];
        
        // Create transfers for existing assigned assets
        foreach ($assignedAssets as $index => $asset) {
            // Only create transfers for some assets
            if ($index % 2 != 0) {
                continue;
            }
            
            // Get current assigned user
            $currentUser = $asset->assignedTo;
            
            if (!$currentUser) {
                continue;
            }
            
            // Get a different user for previous assignment
            $previousUsers = $users->where('id', '!=', $currentUser->id)->values();
            $previousUser = $previousUsers->isNotEmpty() ? $previousUsers->random() : $users->first();
            
            // Create a completed transfer (historical)
            AssetTransfer::create([
                'asset_id' => $asset->id,
                'from_location' => $locations[array_rand($locations)],
                'to_location' => $asset->location,
                'from_user_id' => $previousUser->id,
                'to_user_id' => $currentUser->id,
                'transfer_reason' => $transferReasons[array_rand($transferReasons)],
                'transfer_date' => Carbon::now()->subDays(rand(30, 180)),
                'processed_by' => $users->first()->id,
                'status' => 'completed',
            ]);
            $transferCount++;
            
            // For some assets, create a pending transfer as well
            if ($index % 4 == 0) {
                // Get another different user for future assignment
                $futureUsers = $users->where('id', '!=', $currentUser->id)->values();
                $futureUser = $futureUsers->isNotEmpty() ? $futureUsers->random() : $users->first();
                
                AssetTransfer::create([
                    'asset_id' => $asset->id,
                    'from_location' => $asset->location,
                    'to_location' => $locations[array_rand($locations)],
                    'from_user_id' => $currentUser->id,
                    'to_user_id' => $futureUser->id,
                    'transfer_reason' => $transferReasons[array_rand($transferReasons)],
                    'transfer_date' => Carbon::now(),
                    'processed_by' => $users->first()->id,
                    'status' => 'pending',
                ]);
                $transferCount++;
            }
        }
        
        // Generate additional transfers to reach 100 records
        $remaining = 100 - $transferCount;
        
        $this->command->info("Creating {$remaining} additional asset transfers...");
        
        // Get all assets (not just assigned ones) for more variety
        $allAssets = Asset::all();
        
        for ($i = 0; $i < $remaining; $i++) {
            $asset = $allAssets->random();
            $fromUser = $users->random();
            $toUser = $users->where('id', '!=', $fromUser->id)->random();
            $status = $statuses[array_rand($statuses)];
            
            // For historical transfers
            $transferDate = Carbon::now()->subDays(rand(1, 365));
            
            // For pending or in_progress transfers, use recent or future dates
            if ($status == 'pending' || $status == 'in_progress') {
                $transferDate = Carbon::now()->subDays(rand(0, 14));
            }
            
            AssetTransfer::create([
                'asset_id' => $asset->id,
                'from_location' => $locations[array_rand($locations)],
                'to_location' => $locations[array_rand($locations)],
                'from_user_id' => $fromUser->id,
                'to_user_id' => $toUser->id,
                'transfer_reason' => $transferReasons[array_rand($transferReasons)],
                'transfer_date' => $transferDate,
                'processed_by' => $users->random()->id,
                'status' => $status,
            ]);
        }
        
        $this->command->info('Created ' . AssetTransfer::count() . ' asset transfers in total.');
    }
}