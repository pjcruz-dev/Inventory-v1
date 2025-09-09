<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;
use Illuminate\Support\Facades\DB;

class LocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('locations')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $locations = [
            [
                'name' => 'Main Office - Floor 1',
                'address' => '123 Business Center, Suite 100, Downtown, City 12345'
            ],
            [
                'name' => 'Main Office - Floor 2',
                'address' => '123 Business Center, Suite 200, Downtown, City 12345'
            ],
            [
                'name' => 'Main Office - Floor 3',
                'address' => '123 Business Center, Suite 300, Downtown, City 12345'
            ],
            [
                'name' => 'IT Server Room',
                'address' => '123 Business Center, Basement Level, Downtown, City 12345'
            ],
            [
                'name' => 'Conference Room A',
                'address' => '123 Business Center, Suite 150, Downtown, City 12345'
            ],
            [
                'name' => 'Conference Room B',
                'address' => '123 Business Center, Suite 250, Downtown, City 12345'
            ],
            [
                'name' => 'Warehouse',
                'address' => '456 Industrial Drive, Warehouse District, City 67890'
            ],
            [
                'name' => 'Remote Office - North',
                'address' => '789 North Avenue, North District, City 11111'
            ],
            [
                'name' => 'Remote Office - South',
                'address' => '321 South Street, South District, City 22222'
            ],
            [
                'name' => 'Mobile/Field Work',
                'address' => 'Various locations for field work and mobile assignments'
            ]
        ];

        foreach ($locations as $location) {
            Location::create([
                'name' => $location['name'],
                'address' => $location['address'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}