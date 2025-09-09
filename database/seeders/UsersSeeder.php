<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Don't truncate users table as it might contain the admin user
        // Instead, create additional users if they don't exist

        $userRole = Role::where('name', 'User')->first();
        $managerRole = Role::where('name', 'Manager')->first();

        $users = [
            [
                'employee_no' => 'EMP001',
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => 'john.smith@company.com',
                'department' => 'Information Technology',
                'position' => 'Senior Software Developer',
                'role_id' => $userRole->id ?? 2,
                'status' => 'Active'
            ],
            [
                'employee_no' => 'EMP002',
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'email' => 'sarah.johnson@company.com',
                'department' => 'Human Resources',
                'position' => 'HR Manager',
                'role_id' => $managerRole->id ?? 3,
                'status' => 'Active'
            ],
            [
                'employee_no' => 'EMP003',
                'first_name' => 'Michael',
                'last_name' => 'Brown',
                'email' => 'michael.brown@company.com',
                'department' => 'Finance & Accounting',
                'position' => 'Financial Analyst',
                'role_id' => $userRole->id ?? 2,
                'status' => 'Active'
            ],
            [
                'employee_no' => 'EMP004',
                'first_name' => 'Emily',
                'last_name' => 'Davis',
                'email' => 'emily.davis@company.com',
                'department' => 'Marketing',
                'position' => 'Marketing Specialist',
                'role_id' => $userRole->id ?? 2,
                'status' => 'Active'
            ],
            [
                'employee_no' => 'EMP005',
                'first_name' => 'Robert',
                'last_name' => 'Wilson',
                'email' => 'robert.wilson@company.com',
                'department' => 'Operations',
                'position' => 'Operations Manager',
                'role_id' => $managerRole->id ?? 3,
                'status' => 'Active'
            ],
            [
                'employee_no' => 'EMP006',
                'first_name' => 'Lisa',
                'last_name' => 'Anderson',
                'email' => 'lisa.anderson@company.com',
                'department' => 'Sales',
                'position' => 'Sales Representative',
                'role_id' => $userRole->id ?? 2,
                'status' => 'Active'
            ],
            [
                'employee_no' => 'EMP007',
                'first_name' => 'David',
                'last_name' => 'Martinez',
                'email' => 'david.martinez@company.com',
                'department' => 'Research & Development',
                'position' => 'Research Scientist',
                'role_id' => $userRole->id ?? 2,
                'status' => 'Active'
            ],
            [
                'employee_no' => 'EMP008',
                'first_name' => 'Jennifer',
                'last_name' => 'Taylor',
                'email' => 'jennifer.taylor@company.com',
                'department' => 'Customer Support',
                'position' => 'Support Specialist',
                'role_id' => $userRole->id ?? 2,
                'status' => 'Active'
            ],
            [
                'employee_no' => 'EMP009',
                'first_name' => 'Christopher',
                'last_name' => 'Lee',
                'email' => 'christopher.lee@company.com',
                'department' => 'Legal & Compliance',
                'position' => 'Legal Counsel',
                'role_id' => $userRole->id ?? 2,
                'status' => 'Active'
            ],
            [
                'employee_no' => 'EMP010',
                'first_name' => 'Amanda',
                'last_name' => 'White',
                'email' => 'amanda.white@company.com',
                'department' => 'Information Technology',
                'position' => 'Project Coordinator',
                'role_id' => $userRole->id ?? 2,
                'status' => 'Active'
            ]
        ];

        foreach ($users as $userData) {
            // Check if user already exists
            $existingUser = User::where('email', $userData['email'])->first();
            
            if (!$existingUser) {
                User::create([
                    'employee_no' => $userData['employee_no'],
                    'first_name' => $userData['first_name'],
                    'last_name' => $userData['last_name'],
                    'email' => $userData['email'],
                    'password' => Hash::make('password123'), // Default password
                    'department' => $userData['department'],
                    'position' => $userData['position'],
                    'role_id' => $userData['role_id'],
                    'status' => $userData['status']
                ]);
            }
        }
    }
}