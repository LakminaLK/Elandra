<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Address;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class ERDiagramComplianceSeeder extends Seeder
{
    /**
     * Seed data to match ER diagram exactly
     */
    public function run(): void
    {
        // Get existing users
        $users = User::where('role', 'customer')->take(5)->get();

        if ($users->isEmpty()) {
            $this->command->info('No customer users found. Run AdminUserSeeder first.');
            return;
        }

        // Create addresses for each user (matches ER diagram Address entity)
        foreach ($users as $user) {
            // Create shipping address
            Address::create([
                'user_id' => $user->id,
                'type' => 'shipping',
                'street' => fake()->streetAddress(),
                'city' => fake()->city(),
                'state' => fake()->state(),
                'postal_code' => fake()->postcode(),
                'country' => 'USA',
                'is_default' => true,
            ]);

            // Create billing address (some users)
            if (rand(1, 10) > 6) {
                Address::create([
                    'user_id' => $user->id,
                    'type' => 'billing',
                    'street' => fake()->streetAddress(),
                    'city' => fake()->city(),
                    'state' => fake()->state(),
                    'postal_code' => fake()->postcode(),
                    'country' => 'USA',
                    'is_default' => true,
                ]);
            }
        }

        // Create payments for existing orders (matches ER diagram Payment entity)
        $orders = Order::take(10)->get();
        
        foreach ($orders as $order) {
            Payment::create([
                'order_id' => $order->id,
                'amount' => $order->total_amount,
                'method' => collect(['credit_card', 'paypal', 'stripe', 'bank_transfer'])->random(),
                'status' => collect(['completed', 'pending', 'failed'])->random(),
                'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                'payment_details' => [
                    'gateway' => 'stripe',
                    'currency' => 'USD',
                    'processed_by' => 'system'
                ],
                'processed_at' => rand(1, 10) > 2 ? now()->subDays(rand(1, 30)) : null,
            ]);
        }

        $this->command->info('✅ ER Diagram compliance data seeded successfully!');
        $this->command->info('📊 Created addresses and payments to match ER diagram entities');
    }
}