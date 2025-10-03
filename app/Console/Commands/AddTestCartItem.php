<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cart;
use App\Models\MongoProduct;
use App\Models\User;

class AddTestCartItem extends Command
{
    protected $signature = 'test:add-cart-item {userId?}';
    protected $description = 'Add a test item to a user\'s cart';

    public function handle()
    {
        $userId = $this->argument('userId') ?? 1;
        
        // Get a user
        $user = User::find($userId);
        if (!$user) {
            $this->error("User with ID {$userId} not found");
            return 1;
        }

        // Get a product
        $product = MongoProduct::first();
        if (!$product) {
            $this->error('No products found in MongoDB');
            return 1;
        }

        // Clear existing cart items for this user
        Cart::where('user_id', $userId)->delete();

        // Add test cart item
        $cartItem = Cart::create([
            'user_id' => $userId,
            'product_id' => $product->_id,
            'product_name' => $product->name,
            'quantity' => 1,
            'price' => $product->price ?? 99.99,
        ]);

        $this->info("Added cart item for user {$user->name}:");
        $this->line("- Product: {$product->name}");
        $this->line("- Price: $" . number_format($cartItem->price, 2));
        $this->line("- Quantity: {$cartItem->quantity}");
        $this->line("- Total: $" . number_format($cartItem->total_price, 2));

        return 0;
    }
}