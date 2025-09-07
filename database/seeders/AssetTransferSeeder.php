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
        
        // Create sample asset transfers
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
                'from_location' => 'Previous Department',
                'to_location' => $asset->location,
                'from_user_id' => $previousUser->id,
                'to_user_id' => $currentUser->id,
                'transfer_reason' => 'Employee reassignment',
                'transfer_date' => Carbon::now()->subDays(rand(30, 180)),
                'processed_by' => $users->first()->id,
                'status' => 'completed',
            ]);
            
            // For some assets, create a pending transfer as well
            if ($index % 4 == 0) {
                // Get another different user for future assignment
                $futureUsers = $users->where('id', '!=', $currentUser->id)->values();
                $futureUser = $futureUsers->isNotEmpty() ? $futureUsers->random() : $users->first();
                
                AssetTransfer::create([
                    'asset_id' => $asset->id,
                    'from_location' => $asset->location,
                    'to_location' => 'New Department',
                    'from_user_id' => $currentUser->id,
                    'to_user_id' => $futureUser->id,
                    'transfer_reason' => 'Department restructuring',
                    'transfer_date' => Carbon::now(),
                    'processed_by' => $users->first()->id,
                    'status' => 'pending',
                ]);
            }
        }
    }
}