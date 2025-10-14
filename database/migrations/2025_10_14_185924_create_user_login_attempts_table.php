<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_login_attempts', function (Blueprint $table) {
            $table->id();
            $table->string('email'); // User email that attempted login
            $table->string('ip_address'); // IP address of the attempt
            $table->integer('attempts_count')->default(1); // Number of failed attempts
            $table->timestamp('locked_until')->nullable(); // When the lockout expires
            $table->boolean('is_successful')->default(false); // Track successful attempts too
            $table->text('user_agent')->nullable(); // Browser/device info
            $table->timestamps();
            
            // Index for faster lookups
            $table->index(['email', 'ip_address']);
            $table->index(['locked_until']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_login_attempts');
    }
};
