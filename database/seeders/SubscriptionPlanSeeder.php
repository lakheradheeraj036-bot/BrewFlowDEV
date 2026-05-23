<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'slug' => 'free',
                'description' => 'Basic features for getting started',
                'price' => 0,
                'currency' => 'INR',
                'interval' => 'month',
                'interval_count' => 1,
                'features' => [
                    'Basic dashboard access',
                    'Up to 5 staff members',
                    'Limited support',
                    'Standard reporting',
                ],
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'description' => 'Perfect for small businesses',
                'price' => 29,
                'currency' => 'INR',
                'interval' => 'month',
                'interval_count' => 1,
                'features' => [
                    'All Free features',
                    'Up to 20 staff members',
                    'Priority email support',
                    'Advanced reporting',
                    'Inventory management',
                    'Basic analytics',
                ],
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Professional',
                'slug' => 'professional',
                'description' => 'For growing businesses',
                'price' => 79,
                'currency' => 'INR',
                'interval' => 'month',
                'interval_count' => 1,
                'features' => [
                    'All Starter features',
                    'Unlimited staff members',
                    '24/7 phone support',
                    'Custom integrations',
                    'Advanced analytics',
                    'Multi-location support',
                    'API access',
                ],
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'description' => 'Custom pricing for large organizations',
                'price' => 0,
                'currency' => 'INR',
                'interval' => 'month',
                'interval_count' => 1,
                'features' => [
                    'All Professional features',
                    'Dedicated account manager',
                    'Custom development',
                    'White-label options',
                    'SLA guarantee',
                    'Unlimited everything',
                    'On-premise deployment option',
                ],
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }

        $this->command->info('Subscription plans seeded successfully.');
    }
}
