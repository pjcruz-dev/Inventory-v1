<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Manufacturer;
use Illuminate\Support\Facades\DB;

class ManufacturersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('manufacturers')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $manufacturers = [
            ['name' => 'Dell Technologies', 'website' => 'https://www.dell.com', 'contact_email' => 'support@dell.com', 'description' => 'Leading technology company providing computers and IT solutions'],
            ['name' => 'HP Inc.', 'website' => 'https://www.hp.com', 'contact_email' => 'support@hp.com', 'description' => 'Global technology company specializing in personal computers and printers'],
            ['name' => 'Lenovo Group', 'website' => 'https://www.lenovo.com', 'contact_email' => 'support@lenovo.com', 'description' => 'Multinational technology company manufacturing computers and mobile devices'],
            ['name' => 'Apple Inc.', 'website' => 'https://www.apple.com', 'contact_email' => 'support@apple.com', 'description' => 'Technology company known for consumer electronics and software'],
            ['name' => 'Microsoft Corporation', 'website' => 'https://www.microsoft.com', 'contact_email' => 'support@microsoft.com', 'description' => 'Software corporation developing operating systems and productivity software'],
            ['name' => 'Cisco Systems', 'website' => 'https://www.cisco.com', 'contact_email' => 'support@cisco.com', 'description' => 'Networking hardware and telecommunications equipment manufacturer'],
            ['name' => 'Samsung Electronics', 'website' => 'https://www.samsung.com', 'contact_email' => 'support@samsung.com', 'description' => 'Electronics company manufacturing smartphones, computers, and displays'],
            ['name' => 'ASUS', 'website' => 'https://www.asus.com', 'contact_email' => 'support@asus.com', 'description' => 'Computer hardware and electronics manufacturer'],
            ['name' => 'Acer Inc.', 'website' => 'https://www.acer.com', 'contact_email' => 'support@acer.com', 'description' => 'Hardware and electronics corporation specializing in computers'],
            ['name' => 'IBM Corporation', 'website' => 'https://www.ibm.com', 'contact_email' => 'support@ibm.com', 'description' => 'Technology and consulting company providing enterprise solutions']
        ];

        foreach ($manufacturers as $manufacturer) {
            Manufacturer::create([
                'name' => $manufacturer['name'],
                'website' => $manufacturer['website'],
                'contact_email' => $manufacturer['contact_email'],
                'description' => $manufacturer['description'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}