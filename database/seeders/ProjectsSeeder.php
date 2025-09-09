<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Department;
use Illuminate\Support\Facades\DB;

class ProjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('projects')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $itDept = Department::where('name', 'Information Technology')->first();
        $hrDept = Department::where('name', 'Human Resources')->first();
        $financeDept = Department::where('name', 'Finance & Accounting')->first();
        $marketingDept = Department::where('name', 'Marketing')->first();
        $operationsDept = Department::where('name', 'Operations')->first();
        $salesDept = Department::where('name', 'Sales')->first();
        $rdDept = Department::where('name', 'Research & Development')->first();
        $supportDept = Department::where('name', 'Customer Support')->first();
        $legalDept = Department::where('name', 'Legal & Compliance')->first();
        $facilitiesDept = Department::where('name', 'Facilities Management')->first();

        $projects = [
            [
                'name' => 'Digital Transformation Initiative',
                'description' => 'Modernize legacy systems and implement cloud-based solutions',
                'department_id' => $itDept->id ?? 1
            ],
            [
                'name' => 'Brand Refresh Campaign',
                'description' => 'Complete rebranding including logo, website, and marketing materials',
                'department_id' => $marketingDept->id ?? 5
            ],
            [
                'name' => 'Next-Gen Product Development',
                'description' => 'Research and development of innovative product line',
                'department_id' => $rdDept->id ?? 7
            ],
            [
                'name' => 'Process Optimization Project',
                'description' => 'Streamline operational processes and reduce inefficiencies',
                'department_id' => $operationsDept->id ?? 4
            ],
            [
                'name' => 'Customer Portal Enhancement',
                'description' => 'Upgrade customer-facing portal with new features and improved UX',
                'department_id' => $supportDept->id ?? 8
            ],
            [
                'name' => 'Sales Automation System',
                'description' => 'Implement CRM and sales automation tools',
                'department_id' => $marketingDept->id ?? 5
            ],
            [
                'name' => 'Mobile App Development',
                'description' => 'Develop native mobile applications for iOS and Android',
                'department_id' => $itDept->id ?? 1
            ],
            [
                'name' => 'Data Analytics Platform',
                'description' => 'Build comprehensive data analytics and reporting platform',
                'department_id' => $itDept->id ?? 1
            ],
            [
                'name' => 'Security Infrastructure Upgrade',
                'description' => 'Enhance cybersecurity measures and implement zero-trust architecture',
                'department_id' => $itDept->id ?? 1
            ],
            [
                'name' => 'Sustainability Initiative',
                'description' => 'Implement green technologies and sustainable business practices',
                'department_id' => $facilitiesDept->id ?? 10
            ]
        ];

        foreach ($projects as $project) {
            Project::create([
                'name' => $project['name'],
                'description' => $project['description'],
                'department_id' => $project['department_id'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}