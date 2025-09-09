<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use Illuminate\Support\Facades\DB;

class DepartmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('departments')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $departments = [
            [
                'name' => 'Information Technology',
                'description' => 'Manages IT infrastructure, software development, and technical support'
            ],
            [
                'name' => 'Human Resources',
                'description' => 'Handles employee relations, recruitment, and organizational development'
            ],
            [
                'name' => 'Finance & Accounting',
                'description' => 'Manages financial planning, accounting, and budget control'
            ],
            [
                'name' => 'Marketing',
                'description' => 'Develops marketing strategies and manages brand communications'
            ],
            [
                'name' => 'Operations',
                'description' => 'Oversees daily operations and process optimization'
            ],
            [
                'name' => 'Sales',
                'description' => 'Manages customer relationships and revenue generation'
            ],
            [
                'name' => 'Research & Development',
                'description' => 'Conducts research and develops new products and services'
            ],
            [
                'name' => 'Customer Support',
                'description' => 'Provides customer service and technical assistance'
            ],
            [
                'name' => 'Legal & Compliance',
                'description' => 'Handles legal matters and ensures regulatory compliance'
            ],
            [
                'name' => 'Facilities Management',
                'description' => 'Manages office spaces, equipment, and building maintenance'
            ]
        ];

        foreach ($departments as $department) {
            Department::create([
                'name' => $department['name'],
                'description' => $department['description'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}