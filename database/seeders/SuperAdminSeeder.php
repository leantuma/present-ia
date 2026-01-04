<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if superadmin already exists
        $existingSuperAdmin = User::where('role', 'superadmin')->first();
        
        if ($existingSuperAdmin) {
            $this->command->warn('Superadmin user already exists. Skipping creation.');
            return;
        }

        // Create superadmin user
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@present-ia.com',
            'password' => Hash::make('password'),
            'company_id' => null, // Superadmin no pertenece a ninguna empresa
            'role' => 'superadmin',
        ]);

        $this->command->info('Superadmin user created successfully!');
        $this->command->info('Email: superadmin@present-ia.com');
        $this->command->info('Password: password');
    }
}
