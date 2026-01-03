<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use App\Models\EmployeeProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = Company::first();

        if (!$company) {
            $this->command->warn('No company found. Please run CompanySeeder first.');
            return;
        }

        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@present-ia.com',
            'password' => Hash::make('password'),
            'company_id' => $company->id,
            'role' => 'admin',
        ]);

        // Create supervisor user
        $supervisor = User::create([
            'name' => 'Supervisor User',
            'email' => 'supervisor@present-ia.com',
            'password' => Hash::make('password'),
            'company_id' => $company->id,
            'role' => 'supervisor',
        ]);

        // Create employee users
        for ($i = 1; $i <= 5; $i++) {
            $employee = User::create([
                'name' => "Employee {$i}",
                'email' => "employee{$i}@present-ia.com",
                'password' => Hash::make('password'),
                'company_id' => $company->id,
                'role' => 'employee',
            ]);

            EmployeeProfile::create([
                'user_id' => $employee->id,
                'company_id' => $company->id,
                'employee_id' => "EMP{$i}",
                'department' => fake()->randomElement(['Sales', 'Marketing', 'IT', 'HR', 'Operations']),
                'position' => fake()->randomElement(['Junior', 'Senior', 'Lead']) . ' ' . fake()->jobTitle(),
                'hire_date' => fake()->dateTimeBetween('-2 years', 'now'),
            ]);
        }
    }
}
