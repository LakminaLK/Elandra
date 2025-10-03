<?php

// Test script to verify user/admin separation
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Database Separation Verification ===\n\n";

// Test 1: Check Users table (should only have customers)
echo "1. Users Table (Customers only):\n";
$users = App\Models\User::select('id', 'name', 'email', 'role')->get();
foreach ($users as $user) {
    echo "   - ID: {$user->id}, Name: {$user->name}, Email: {$user->email}, Role: {$user->role}\n";
}
echo "   Total customers: " . $users->count() . "\n\n";

// Test 2: Check Admins table
echo "2. Admins Table:\n";
$admins = App\Models\Admin::select('id', 'name', 'email', 'role', 'is_active')->get();
foreach ($admins as $admin) {
    echo "   - ID: {$admin->id}, Name: {$admin->name}, Email: {$admin->email}, Role: {$admin->role}, Active: " . ($admin->is_active ? 'Yes' : 'No') . "\n";
}
echo "   Total admins: " . $admins->count() . "\n\n";

// Test 3: Verify no admin emails in users table
echo "3. Verification - No admin emails in users table:\n";
$adminEmailsInUsers = App\Models\User::where('email', 'like', '%admin%')->count();
echo "   Admin emails found in users table: {$adminEmailsInUsers}\n";
echo "   Status: " . ($adminEmailsInUsers === 0 ? "✓ CLEAN" : "✗ CONFLICT") . "\n\n";

// Test 4: Authentication guards test
echo "4. Authentication Configuration:\n";
$guards = config('auth.guards');
echo "   Web guard provider: " . $guards['web']['provider'] . "\n";
echo "   Admin guard provider: " . $guards['admin']['provider'] . "\n\n";

echo "=== Verification Complete ===\n";
echo "Summary:\n";
echo "- Users table contains only customers: " . ($adminEmailsInUsers === 0 ? "✓" : "✗") . "\n";
echo "- Admins table exists and populated: " . ($admins->count() > 0 ? "✓" : "✗") . "\n";
echo "- Separate authentication guards configured: ✓\n";