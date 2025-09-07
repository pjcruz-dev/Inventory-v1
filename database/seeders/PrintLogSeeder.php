<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\PrintLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PrintLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get assets and users for reference
        $assets = Asset::all();
        $users = User::all();
        
        if ($assets->isEmpty()) {
            $this->command->info('No assets found. Please run AssetSeeder first.');
            return;
        }
        
        if ($users->isEmpty()) {
            $this->command->info('No users found. Please run UserSeeder first.');
            return;
        }
        
        // Print formats
        $printFormats = ['label', 'detail_report', 'summary'];
        
        // Printers
        $printers = [
            'HP LaserJet Pro - Finance',
            'Epson WorkForce - HR',
            'Brother MFC - Reception',
            'Canon ImageRunner - IT Department',
            'Zebra Label Printer - Warehouse',
        ];
        
        // Create sample print logs
        foreach ($assets as $index => $asset) {
            // Only create print logs for some assets
            if ($index % 3 != 0) {
                continue;
            }
            
            // Create 1-3 print logs per selected asset
            $numLogs = rand(1, 3);
            
            for ($i = 0; $i < $numLogs; $i++) {
                $printFormat = $printFormats[array_rand($printFormats)];
                $printer = $printers[array_rand($printers)];
                $daysAgo = rand(1, 90);
                
                PrintLog::create([
                    'asset_id' => $asset->id,
                    'printed_by' => $users->random()->id,
                    'printed_at' => Carbon::now()->subDays($daysAgo),
                    'print_format' => $printFormat,
                    'copies' => rand(1, 5),
                    'destination_printer' => $printer,
                    'note' => $this->getNoteForPrintFormat($printFormat),
                ]);
            }
        }
    }
    
    /**
     * Get a relevant note based on the print format.
     */
    private function getNoteForPrintFormat($printFormat)
    {
        switch ($printFormat) {
            case 'label':
                return 'Asset label printed for inventory tagging';
            case 'detail_report':
                return 'Detailed asset report printed for audit purposes';
            case 'summary':
                return 'Summary report printed for management review';
            default:
                return 'Asset information printed';
        }
    }
}