<?php

namespace Database\Seeders;

use App\Models\Business;
use Illuminate\Database\Seeder;

class BusinessSeeder extends Seeder
{
    public function run(): void
    {
        $businesses = [
            ['name' => 'The Daily Grind Cafe',   'type' => 'cafe',       'status' => 'active',   'city' => 'Sydney'],
            ['name' => 'Harbor View Hotel',       'type' => 'hotel',      'status' => 'active',   'city' => 'Melbourne'],
            ['name' => 'Sunrise Coffee Co.',      'type' => 'cafe',       'status' => 'active',   'city' => 'Brisbane'],
            ['name' => 'The Craft Bar',           'type' => 'bar',        'status' => 'pending',  'city' => 'Perth'],
            ['name' => 'Bella Vista Restaurant',  'type' => 'restaurant', 'status' => 'active',   'city' => 'Adelaide'],
            ['name' => 'Metro Espresso',          'type' => 'cafe',       'status' => 'inactive', 'city' => 'Sydney'],
        ];

        foreach ($businesses as $data) {
            Business::firstOrCreate(
                ['name' => $data['name']],
                array_merge($data, [
                    'email'             => strtolower(str_replace([' ', '.'], ['.', ''], $data['name'])) . '@example.com',
                    'phone'             => '+61 4' . rand(10, 99) . ' ' . rand(100, 999) . ' ' . rand(100, 999),
                    'country'           => 'Australia',
                    'timezone'          => 'Australia/Sydney',
                    'currency'          => 'AUD',
                    'subscription_plan' => 'professional',
                ])
            );
        }
    }
}
