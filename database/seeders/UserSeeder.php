<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if admin user exists before creating
        if (!DB::table('users')->where('id', 1)->exists()) {
            // Create admin user
            DB::table('users')->insert([
                'id' => 1,
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('123123123'),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } else {
            $this->command->info('Admin user already exists, skipping creation.');
        }
        
        // Create additional users to support our seeders
        $this->command->info('Creating additional users...');
        
        $departments = ['IT', 'Finance', 'HR', 'Marketing', 'Engineering', 'Sales', 'Operations', 'Customer Support'];
        $positions = ['Manager', 'Director', 'Specialist', 'Coordinator', 'Analyst', 'Assistant', 'Lead', 'Supervisor'];
        
        // Create at least 30 users to support our asset transfers and assignments
        for ($i = 2; $i <= 30; $i++) {
            $department = $departments[array_rand($departments)];
            $position = $positions[array_rand($positions)];
            $name = $department . ' ' . $position . ' ' . $i;
            
            DB::table('users')->insert([
                'name' => $name,
                'email' => strtolower(str_replace(' ', '.', $name)) . '@inventory.test',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        $this->command->info('Created ' . DB::table('users')->count() . ' users in total.');
    }
}
