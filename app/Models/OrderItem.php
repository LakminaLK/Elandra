<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_sku',
        'quantity',
        'unit_price',
        'total_price',
        'product_options',
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
            'unit_price' => 'decimal:2',
            'total_price' => 'decimal:2',
            'product_options' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Boot the model and set up event listeners.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically calculate total_price when saving (if not set)
        static::saving(function ($orderItem) {
            if (is_null($orderItem->total_price) && $orderItem->unit_price && $orderItem->quantity) {
                $orderItem->total_price = $orderItem->unit_price * $orderItem->quantity;
            }
        });
    }

    /**
     * Get the order that owns the order item.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the MongoDB product that is in the order item.
     * Note: This creates a custom relationship since we store MongoDB ObjectId as string.
     */
    public function mongoProduct()
    {
        return \App\Models\MongoProduct::where('_id', $this->product_id)->first();
    }

    /**
     * Legacy product relationship - kept for backward compatibility but returns null
     * since we're using MongoDB products now.
     */
    public function product()
    {
        return null; // No longer using SQL Product model
    }

    /**
     * Get the formatted unit price.
     */
    public function getFormattedUnitPriceAttribute(): string
    {
        return '$' . number_format($this->unit_price, 2);
    }

    /**
     * Get the formatted total price.
     */
    public function getFormattedTotalPriceAttribute(): string
    {
        return '$' . number_format($this->total_price, 2);
    }
    
    /**
     * Get the formatted price (backward compatibility).
     */
    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->unit_price, 2);
    }

    /**
     * Get the formatted total (backward compatibility).
     */
    public function getFormattedTotalAttribute(): string
    {
        return '$' . number_format($this->total_price, 2);
    }

    /**
     * Get the product name (fallback to stored name if product is deleted).
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->product ? $this->product->name : $this->product_name;
    }

    /**
     * Get the product SKU (fallback to stored SKU if product is deleted).
     */
    public function getDisplaySkuAttribute(): string
    {
        return $this->product ? $this->product->sku : $this->product_sku;
    }
}
