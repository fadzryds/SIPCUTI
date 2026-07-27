<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            [
                'email' => 'admin@sipcuti.com',
            ],
            [
                'name' => 'Super Administrator',
                'password' => bcrypt('Admin12345'),
            ]
        );

        $admin->assignRole('Super Admin');
    }
}