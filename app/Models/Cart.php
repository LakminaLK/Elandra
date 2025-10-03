<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'product_name',
        'product_image',
        'product_sku',
        'quantity',
        'price',
        'original_price',
        'is_sale',
        'product_options',
        'added_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'price' => 'decimal:2',
            'original_price' => 'decimal:2',
            'is_sale' => 'boolean',
            'product_options' => 'array',
            'added_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the cart item.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the MongoDB product that is in the cart.
     * Since we're using MongoDB for products, we'll fetch it manually
     */
    public function getProductAttribute()
    {
        return \App\Models\MongoProduct::find($this->product_id);
    }

    /**
     * Check if the product still exists in MongoDB
     */
    public function productExists(): bool
    {
        return \App\Models\MongoProduct::where('_id', $this->product_id)->exists();
    }

    /**
     * Scope a query to only include items for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include items for a specific session.
     */
    public function scopeForSession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    /**
     * Get the total price for this cart item.
     */
    public function getTotalPriceAttribute(): float
    {
        return $this->price * $this->quantity;
    }

    /**
     * Get the formatted total price for this cart item.
     */
    public function getFormattedTotalPriceAttribute(): string
    {
        return '$' . number_format($this->total_price, 2);
    }

    /**
     * Get the formatted price for this cart item.
     */
    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->price, 2);
    }

    /**
     * Get cart items for current user or session
     */
    public static function getCartItems($userId = null, $sessionId = null)
    {
        $query = self::query();
        
        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($sessionId) {
            $query->where('session_id', $sessionId)->whereNull('user_id');
        }
        
        return $query->orderBy('added_at', 'desc')->get();
    }

    /**
     * Get cart total count
     */
    public static function getCartCount($userId = null, $sessionId = null): int
    {
        $query = self::query();
        
        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($sessionId) {
            $query->where('session_id', $sessionId)->whereNull('user_id');
        }
        
        return $query->sum('quantity');
    }

    /**
     * Add product to cart
     */
    public static function addProduct($productId, $quantity = 1, $userId = null, $sessionId = null)
    {
        // Get product from MongoDB
        $product = \App\Models\MongoProduct::find($productId);
        
        if (!$product) {
            throw new \Exception('Product not found');
        }

        // Check if item already exists in cart
        $query = self::query()->where('product_id', $productId);
        
        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($sessionId) {
            $query->where('session_id', $sessionId)->whereNull('user_id');
        }
        
        $existingItem = $query->first();
        
        if ($existingItem) {
            // Update quantity
            $existingItem->increment('quantity', $quantity);
            return $existingItem;
        } else {
            // Create new cart item
            $price = $product->sale_price && $product->sale_price < $product->price 
                ? $product->sale_price 
                : $product->price;
                
            return self::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => (string) $product->_id,
                'product_name' => $product->name,
                'product_image' => isset($product->images[0]) ? $product->images[0] : null,
                'product_sku' => $product->sku,
                'quantity' => $quantity,
                'price' => $price,
                'original_price' => $product->price,
                'is_sale' => $product->sale_price && $product->sale_price < $product->price,
                'added_at' => now(),
            ]);
        }
    }

    /**
     * Update the quantity of the cart item.
     */
    public function updateQuantity(int $quantity): void
    {
        $this->update(['quantity' => $quantity]);
    }

    /**
     * Increment the quantity of the cart item.
     */
    public function incrementQuantity(int $amount = 1): void
    {
        $this->increment('quantity', $amount);
    }

    /**
     * Decrement the quantity of the cart item.
     */
    public function decrementQuantity(int $amount = 1): void
    {
        $newQuantity = $this->quantity - $amount;
        if ($newQuantity <= 0) {
            $this->delete();
        } else {
            $this->decrement('quantity', $amount);
        }
    }
}
