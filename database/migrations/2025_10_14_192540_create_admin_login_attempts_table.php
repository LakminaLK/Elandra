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
        Schema::create('admin_login_attempts', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('ip_address');
            $table->integer('attempts_count')->default(0);
            $table->timestamp('locked_until')->nullable();
            $table->boolean('is_successful')->default(false);
            $table->text('user_agent')->nullable();
            $table->timestamps();
            
            $table->index(['email', 'ip_address', 'is_successful']);
            $table->index('locked_until');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_login_attempts');
    }
};
