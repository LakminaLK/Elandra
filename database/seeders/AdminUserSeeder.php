<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@elandra.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '+1234567890',
            'address' => [
                'street' => '123 Admin Street',
                'city' => 'Admin City',
                'state' => 'Admin State',
                'zip' => '12345',
                'country' => 'USA'
            ],
            'email_verified_at' => now(),
        ]);

        // Create test customer user
        User::create([
            'name' => 'John Customer',
            'email' => 'customer@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'phone' => '+0987654321',
            'address' => [
                'street' => '456 Customer Ave',
                'city' => 'Customer City',
                'state' => 'Customer State',
                'zip' => '54321',
                'country' => 'USA'
            ],
            'email_verified_at' => now(),
        ]);

        // Create another test customer
        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'phone' => '+1122334455',
            'address' => [
                'street' => '789 Customer Blvd',
                'city' => 'Customer City',
                'state' => 'Customer State',
                'zip' => '67890',
                'country' => 'USA'
            ],
            'email_verified_at' => now(),
        ]);
    }
}
