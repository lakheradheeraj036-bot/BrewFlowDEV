<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin account
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@brewflow.com'],
            [
                'name'               => 'Super Admin',
                'password'           => Hash::make('password'),
                'email_verified_at'  => now(),
                'status'             => 'active',
            ]
        );
        $superAdmin->assignRole('super_admin');

        // Sample Business Owner account
        $owner = User::firstOrCreate(
            ['email' => 'owner@brewflow.com'],
            [
                'name'               => 'Coffee House Owner',
                'password'           => Hash::make('password'),
                'email_verified_at'  => now(),
                'status'             => 'active',
            ]
        );
        $owner->assignRole('business_owner');
    }
}
