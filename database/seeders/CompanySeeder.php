<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a demo company
        Company::create([
            'name' => 'Demo Company',
            'slug' => 'demo-company',
            'email' => 'admin@democompany.com',
            'phone' => '+1-555-0123',
            'address' => '123 Business Street, City, State 12345',
            'is_active' => true,
            'settings' => null,
        ]);
    }
}
