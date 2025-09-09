<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;

class VendorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('vendors')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $vendors = [
            [
                'name' => 'TechSupply Solutions',
                'contact_person' => 'John Smith',
                'email' => 'john.smith@techsupply.com',
                'phone' => '+1-555-0101',
                'address' => '123 Technology Drive, Tech City, TC 12345'
            ],
            [
                'name' => 'Office Equipment Pro',
                'contact_person' => 'Sarah Johnson',
                'email' => 'sarah.johnson@officeequippro.com',
                'phone' => '+1-555-0102',
                'address' => '456 Business Avenue, Commerce City, CC 67890'
            ],
            [
                'name' => 'Network Systems Inc.',
                'contact_person' => 'Michael Brown',
                'email' => 'michael.brown@networksystems.com',
                'phone' => '+1-555-0103',
                'address' => '789 Network Boulevard, Data City, DC 11111'
            ],
            [
                'name' => 'Digital Solutions Ltd.',
                'contact_person' => 'Emily Davis',
                'email' => 'emily.davis@digitalsolutions.com',
                'phone' => '+1-555-0104',
                'address' => '321 Digital Street, Innovation City, IC 22222'
            ],
            [
                'name' => 'Hardware Depot',
                'contact_person' => 'Robert Wilson',
                'email' => 'robert.wilson@hardwaredepot.com',
                'phone' => '+1-555-0105',
                'address' => '654 Hardware Lane, Supply City, SC 33333'
            ],
            [
                'name' => 'IT Resources Group',
                'contact_person' => 'Lisa Anderson',
                'email' => 'lisa.anderson@itresources.com',
                'phone' => '+1-555-0106',
                'address' => '987 IT Plaza, Resource City, RC 44444'
            ],
            [
                'name' => 'Computer World',
                'contact_person' => 'David Martinez',
                'email' => 'david.martinez@computerworld.com',
                'phone' => '+1-555-0107',
                'address' => '147 Computer Way, Tech Valley, TV 55555'
            ],
            [
                'name' => 'Enterprise Solutions',
                'contact_person' => 'Jennifer Taylor',
                'email' => 'jennifer.taylor@enterprisesolutions.com',
                'phone' => '+1-555-0108',
                'address' => '258 Enterprise Road, Business Park, BP 66666'
            ],
            [
                'name' => 'Mobile Tech Suppliers',
                'contact_person' => 'Christopher Lee',
                'email' => 'christopher.lee@mobiletech.com',
                'phone' => '+1-555-0109',
                'address' => '369 Mobile Street, Wireless City, WC 77777'
            ],
            [
                'name' => 'Security Systems Pro',
                'contact_person' => 'Amanda White',
                'email' => 'amanda.white@securitysystems.com',
                'phone' => '+1-555-0110',
                'address' => '741 Security Avenue, Safe City, SC 88888'
            ]
        ];

        foreach ($vendors as $vendor) {
            Vendor::create([
                'name' => $vendor['name'],
                'contact_person' => $vendor['contact_person'],
                'email' => $vendor['email'],
                'phone' => $vendor['phone'],
                'address' => $vendor['address'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}