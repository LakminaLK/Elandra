<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, migrate data from phone to mobile if mobile is empty
        DB::statement('UPDATE users SET mobile = phone WHERE mobile IS NULL AND phone IS NOT NULL');
        
        Schema::table('users', function (Blueprint $table) {
            // Remove duplicate phone column (we'll keep mobile)
            $table->dropColumn('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Re-add phone column
            $table->string('phone')->nullable()->after('mobile');
        });
        
        // Copy mobile data back to phone
        DB::statement('UPDATE users SET phone = mobile WHERE phone IS NULL AND mobile IS NOT NULL');
    }
};
