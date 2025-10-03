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
        Schema::table('carts', function (Blueprint $table) {
            // Drop the foreign key constraint to products table since we're using MongoDB
            $table->dropForeign(['product_id']);
            
            // Change product_id to string to store MongoDB ObjectId
            $table->string('product_id')->change();
            
            // Add additional useful fields
            $table->string('product_name')->after('product_id'); // Cache product name for faster display
            $table->string('product_image')->nullable()->after('product_name'); // Cache product image
            $table->string('product_sku')->nullable()->after('product_image'); // Cache product SKU
            $table->decimal('original_price', 10, 2)->after('price'); // Store original price
            $table->boolean('is_sale')->default(false)->after('original_price'); // Track if item was on sale
            $table->timestamp('added_at')->nullable()->after('product_options'); // When item was added to cart
            $table->index(['user_id', 'session_id']); // Add index for faster queries
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            // Remove added fields
            $table->dropColumn(['product_name', 'product_image', 'product_sku', 'original_price', 'is_sale', 'added_at']);
            $table->dropIndex(['user_id', 'session_id']);
            
            // Change product_id back to unsignedBigInteger
            $table->unsignedBigInteger('product_id')->change();
            
            // Re-add foreign key constraint (though this may fail if MongoDB products exist)
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }
};
