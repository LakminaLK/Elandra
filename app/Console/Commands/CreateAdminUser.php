<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create {--email=admin@admin.com} {--password=admin123} {--name=Administrator}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->option('email');
        $password = $this->option('password');
        $name = $this->option('name');

        // Check if admin already exists
        $existingAdmin = Admin::where('email', $email)->first();
        
        if ($existingAdmin) {
            $this->info("Admin user with email {$email} already exists!");
            $this->info("ID: {$existingAdmin->id}");
            $this->info("Name: {$existingAdmin->name}");
            $this->info("Email: {$existingAdmin->email}");
            $this->info("Role: {$existingAdmin->role}");
            $this->info("Active: " . ($existingAdmin->is_active ? 'Yes' : 'No'));
            
            if ($this->confirm('Do you want to update the password?')) {
                $existingAdmin->update(['password' => Hash::make($password)]);
                $this->info("Password updated successfully!");
            }
            
            return;
        }

        // Create new admin
        $admin = Admin::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'super_admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $this->info("Admin user created successfully!");
        $this->info("ID: {$admin->id}");
        $this->info("Name: {$admin->name}");
        $this->info("Email: {$admin->email}");
        $this->info("Password: {$password}");
        $this->info("Role: {$admin->role}");
        
        $this->info("\n=== LOGIN CREDENTIALS ===");
        $this->info("URL: /admin/login");
        $this->info("Email: {$email}");
        $this->info("Password: {$password}");
        $this->info("=========================");
    }
}