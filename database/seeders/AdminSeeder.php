<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default super admin
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'permissions' => ['*'], // All permissions
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create additional admin users if needed
        Admin::create([
            'name' => 'Admin User',
            'email' => 'admin.user@mail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'permissions' => [
                'users.view',
                'users.edit',
                'transactions.view',
                'transactions.edit',
                'reports.view',
            ],
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        Admin::create([
            'name' => 'Moderator',
            'email' => 'moderator@mail.com',
            'password' => Hash::make('password'),
            'role' => 'moderator',
            'permissions' => [
                'users.view',
                'transactions.view',
                'reports.view',
            ],
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $this->command->info('Admin users created successfully!');
        $this->command->info('Default admin: admin@mail.com / password');
    }
}
