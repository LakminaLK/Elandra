<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class MongoProduct extends Model
{
    use SoftDeletes;

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * The collection associated with the model.
     *
     * @var string
     */
    protected $collection = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'sale_price',
        'sku',
        'stock_quantity',
        'category',
        'brand',
        'weight',
        'dimensions',
        'images',
        'is_active',
        'is_featured',
        'meta_title',
        'meta_description',
        'tags',
        'attributes',
        'status',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'stock_quantity' => 'integer',
            'weight' => 'decimal:2',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'images' => 'array',
            'tags' => 'array',
            'attributes' => 'array',
            'dimensions' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * The "booting" method of the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
            if (empty($product->sku)) {
                $product->sku = 'SKU-' . strtoupper(Str::random(8));
            }
            $product->created_by = auth()->user()->id ?? null;
        });

        static::updating(function ($product) {
            if ($product->isDirty('name') && empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
            $product->updated_by = auth()->user()->id ?? null;
        });
    }

    /**
     * Scope a query to only include active products.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include featured products.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get the product's main image.
     */
    public function getMainImageAttribute()
    {
        if (!empty($this->images) && is_array($this->images)) {
            return $this->images[0] ?? null;
        }
        return null;
    }

    /**
     * Get the product's formatted price.
     */
    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price, 2);
    }

    /**
     * Get the product's formatted sale price.
     */
    public function getFormattedSalePriceAttribute()
    {
        return $this->sale_price ? '$' . number_format($this->sale_price, 2) : null;
    }

    /**
     * Check if product is on sale.
     */
    public function getIsOnSaleAttribute()
    {
        return !empty($this->sale_price) && $this->sale_price < $this->price;
    }

    /**
     * Get the discount percentage.
     */
    public function getDiscountPercentageAttribute()
    {
        if ($this->is_on_sale) {
            return round((($this->price - $this->sale_price) / $this->price) * 100);
        }
        return 0;
    }

    /**
     * Get stock status.
     */
    public function getStockStatusAttribute()
    {
        if ($this->stock_quantity > 10) {
            return 'in-stock';
        } elseif ($this->stock_quantity > 0) {
            return 'low-stock';
        }
        return 'out-of-stock';
    }

    /**
     * Get stock status label.
     */
    public function getStockStatusLabelAttribute()
    {
        return match($this->stock_status) {
            'in-stock' => 'In Stock',
            'low-stock' => 'Low Stock',
            'out-of-stock' => 'Out of Stock',
            default => 'Unknown'
        };
    }

    /**
     * Search products by name or description.
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'regex', "/$term/i")
              ->orWhere('description', 'regex', "/$term/i")
              ->orWhere('sku', 'regex', "/$term/i");
        });
    }

    /**
     * Get products with low stock.
     */
    public function scopeLowStock($query, $threshold = 10)
    {
        return $query->where('stock_quantity', '<=', $threshold)
                    ->where('stock_quantity', '>', 0);
    }

    /**
     * Get out of stock products.
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('stock_quantity', 0);
    }
}