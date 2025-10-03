<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CleanUserAdminSeparationSeeder extends Seeder
{
    /**
     * Run the database seeder to clean up user/admin separation.
     */
    public function run(): void
    {
        $this->command->info('Starting user/admin separation cleanup...');

        // 1. Remove any admin records from users table
        $adminUsersRemoved = User::where('email', 'like', '%admin%')
            ->orWhere('name', 'like', '%admin%')
            ->orWhere('role', 'admin')
            ->delete();

        $this->command->info("Removed {$adminUsersRemoved} admin records from users table");

        // 2. Ensure we have clean sample customer users
        $existingUsers = User::count();
        if ($existingUsers < 3) {
            $sampleUsers = [
                [
                    'name' => 'John Customer',
                    'email' => 'customer@example.com',
                    'password' => Hash::make('password'),
                    'role' => 'customer',
                    'phone' => '+1-555-0123',
                    'address' => '123 Main St, Anytown, USA',
                    'email_verified_at' => now(),
                ],
                [
                    'name' => 'Jane Smith',
                    'email' => 'jane@example.com',
                    'password' => Hash::make('password'),
                    'role' => 'customer',
                    'phone' => '+1-555-0124',
                    'address' => '456 Oak Ave, Somewhere, USA',
                    'email_verified_at' => now(),
                ],
                [
                    'name' => 'Test User',
                    'email' => 'testuser@example.com',
                    'password' => Hash::make('password'),
                    'role' => 'customer',
                    'phone' => '+1-555-0125',
                    'address' => '789 Pine St, Elsewhere, USA',
                    'email_verified_at' => now(),
                ],
            ];

            foreach ($sampleUsers as $userData) {
                if (!User::where('email', $userData['email'])->exists()) {
                    User::create($userData);
                    $this->command->info("Created customer user: {$userData['email']}");
                }
            }
        }

        // 3. Ensure admins table has proper admin user
        if (!Admin::where('email', 'admin@elandra.com')->exists()) {
            Admin::create([
                'name' => 'Administrator',
                'email' => 'admin@elandra.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_active' => true,
                'last_login_at' => null,
                'email_verified_at' => now(),
            ]);
            $this->command->info('Created admin user: admin@elandra.com');
        }

        // 4. Create additional admin user for testing
        if (!Admin::where('email', 'manager@elandra.com')->exists()) {
            Admin::create([
                'name' => 'Store Manager',
                'email' => 'manager@elandra.com',
                'password' => Hash::make('manager123'),
                'role' => 'admin',
                'is_active' => true,
                'last_login_at' => null,
                'email_verified_at' => now(),
            ]);
            $this->command->info('Created manager user: manager@elandra.com');
        }

        // 5. Display final counts
        $userCount = User::count();
        $adminCount = Admin::count();
        
        $this->command->info("Final counts:");
        $this->command->info("  - Customer users: {$userCount}");
        $this->command->info("  - Admin users: {$adminCount}");
        
        // 6. Verify no admin emails in users table
        $conflictingUsers = User::where('email', 'like', '%admin%')
            ->orWhere('role', 'admin')
            ->count();
            
        if ($conflictingUsers > 0) {
            $this->command->error("Warning: Still found {$conflictingUsers} admin records in users table!");
        } else {
            $this->command->info('✓ Users table is clean - contains only customers');
        }

        $this->command->info('User/Admin separation cleanup completed successfully!');
    }
}
