<?php

namespace App\Services;

use App\Models\MongoProduct;
use App\Models\Order;
use App\Models\OrderItem;
use App\Events\LowStockAlert;
use App\Events\ProductOutOfStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

/**
 * Advanced Inventory Management Service
 * 
 * This service demonstrates sophisticated business logic for inventory management
 * including real-time stock tracking, automatic reorder points, and predictive analytics.
 * 
 * Features:
 * - Real-time stock tracking with atomic operations
 * - Automatic low stock alerts and reorder points
 * - Stock reservation system for pending orders
 * - Inventory forecasting and analytics
 * - Batch stock operations with rollback capability
 * - Stock movement history and audit trails
 */
class AdvancedInventoryService
{
    /**
     * Low stock threshold (when to trigger alerts)
     */
    const LOW_STOCK_THRESHOLD = 10;

    /**
     * Critical stock threshold (urgent reorder needed)
     */
    const CRITICAL_STOCK_THRESHOLD = 5;

    /**
     * Reserve stock for a pending order
     * This prevents overselling by temporarily allocating stock
     *
     * @param Order $order
     * @return bool
     * @throws \Exception
     */
    public function reserveStock(Order $order): bool
    {
        return DB::transaction(function () use ($order) {
            foreach ($order->orderItems as $orderItem) {
                $product = $orderItem->product;
                
                // Check if enough stock is available
                if ($product->available_stock < $orderItem->quantity) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }

                // Reserve the stock atomically
                $updated = Product::where('id', $product->id)
                    ->where('stock_quantity', '>=', $orderItem->quantity)
                    ->decrement('stock_quantity', $orderItem->quantity);

                if (!$updated) {
                    throw new \Exception("Failed to reserve stock for product: {$product->name}");
                }

                // Log the stock movement
                $this->logStockMovement($product, -$orderItem->quantity, 'reserved', [
                    'order_id' => $order->id,
                    'reason' => 'Stock reserved for order'
                ]);

                // Check for low stock after reservation
                $product->refresh();
                $this->checkStockLevels($product);
            }

            // Mark order as stock reserved
            $order->update(['stock_reserved' => true]);

            Log::info("Stock reserved for order", [
                'order_id' => $order->id,
                'total_items' => $order->orderItems->count()
            ]);

            return true;
        });
    }

    /**
     * Release reserved stock (when order is cancelled)
     *
     * @param Order $order
     * @return bool
     */
    public function releaseStock(Order $order): bool
    {
        return DB::transaction(function () use ($order) {
            if (!$order->stock_reserved) {
                return true; // Nothing to release
            }

            foreach ($order->orderItems as $orderItem) {
                $product = $orderItem->product;

                // Release the reserved stock
                $product->increment('stock_quantity', $orderItem->quantity);

                // Log the stock movement
                $this->logStockMovement($product, $orderItem->quantity, 'released', [
                    'order_id' => $order->id,
                    'reason' => 'Stock released from cancelled order'
                ]);
            }

            // Mark order as stock released
            $order->update(['stock_reserved' => false]);

            Log::info("Stock released for cancelled order", [
                'order_id' => $order->id
            ]);

            return true;
        });
    }

    /**
     * Fulfill order (confirm stock deduction)
     *
     * @param Order $order
     * @return bool
     */
    public function fulfillOrder(Order $order): bool
    {
        return DB::transaction(function () use ($order) {
            foreach ($order->orderItems as $orderItem) {
                $product = $orderItem->product;

                // Log the fulfillment
                $this->logStockMovement($product, -$orderItem->quantity, 'sold', [
                    'order_id' => $order->id,
                    'reason' => 'Product sold and shipped'
                ]);
            }

            // Mark order as fulfilled
            $order->update([
                'stock_reserved' => false,
                'status' => 'fulfilled'
            ]);

            Log::info("Order fulfilled", [
                'order_id' => $order->id
            ]);

            return true;
        });
    }

    /**
     * Bulk update stock levels with validation
     *
     * @param array $stockUpdates
     * @return array
     */
    public function bulkUpdateStock(array $stockUpdates): array
    {
        $results = ['success' => [], 'errors' => []];

        DB::transaction(function () use ($stockUpdates, &$results) {
            foreach ($stockUpdates as $update) {
                try {
                    $product = Product::findOrFail($update['product_id']);
                    $oldStock = $product->stock_quantity;
                    $newStock = $update['quantity'];
                    $reason = $update['reason'] ?? 'Bulk stock update';

                    // Update stock
                    $product->update(['stock_quantity' => $newStock]);

                    // Log the movement
                    $this->logStockMovement($product, $newStock - $oldStock, 'adjustment', [
                        'reason' => $reason,
                        'old_quantity' => $oldStock,
                        'new_quantity' => $newStock
                    ]);

                    // Check stock levels
                    $this->checkStockLevels($product);

                    $results['success'][] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'old_stock' => $oldStock,
                        'new_stock' => $newStock
                    ];

                } catch (\Exception $e) {
                    $results['errors'][] = [
                        'product_id' => $update['product_id'] ?? null,
                        'error' => $e->getMessage()
                    ];
                }
            }
        });

        return $results;
    }

    /**
     * Get inventory analytics and insights
     *
     * @return array
     */
    public function getInventoryAnalytics(): array
    {
        return Cache::remember('inventory_analytics', 300, function () {
            $totalProducts = Product::where('is_active', true)->count();
            $inStockProducts = Product::where('is_active', true)->where('stock_quantity', '>', 0)->count();
            $outOfStockProducts = Product::where('is_active', true)->where('stock_quantity', 0)->count();
            $lowStockProducts = Product::where('is_active', true)
                ->where('stock_quantity', '>', 0)
                ->where('stock_quantity', '<=', self::LOW_STOCK_THRESHOLD)
                ->count();

            $totalStockValue = Product::where('is_active', true)
                ->selectRaw('SUM(stock_quantity * price) as total_value')
                ->value('total_value') ?? 0;

            // Get fast-moving products (top sellers last 30 days)
            $fastMovingProducts = DB::table('order_items')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.created_at', '>=', now()->subDays(30))
                ->where('orders.status', '!=', 'cancelled')
                ->select(
                    'products.id',
                    'products.name',
                    'products.stock_quantity',
                    DB::raw('SUM(order_items.quantity) as total_sold')
                )
                ->groupBy('products.id', 'products.name', 'products.stock_quantity')
                ->orderBy('total_sold', 'desc')
                ->limit(10)
                ->get();

            // Get slow-moving products (low sales last 30 days)
            $slowMovingProducts = Product::where('is_active', true)
                ->where('stock_quantity', '>', 0)
                ->whereDoesntHave('orderItems', function ($query) {
                    $query->whereHas('order', function ($orderQuery) {
                        $orderQuery->where('created_at', '>=', now()->subDays(30))
                               ->where('status', '!=', 'cancelled');
                    });
                })
                ->select('id', 'name', 'stock_quantity', 'price')
                ->limit(10)
                ->get();

            return [
                'overview' => [
                    'total_products' => $totalProducts,
                    'in_stock_products' => $inStockProducts,
                    'out_of_stock_products' => $outOfStockProducts,
                    'low_stock_products' => $lowStockProducts,
                    'stock_availability_rate' => $totalProducts > 0 ? ($inStockProducts / $totalProducts) * 100 : 0,
                    'total_stock_value' => (float) $totalStockValue,
                    'formatted_stock_value' => '$' . number_format($totalStockValue, 2)
                ],
                'alerts' => [
                    'out_of_stock' => Product::where('is_active', true)
                        ->where('stock_quantity', 0)
                        ->select('id', 'name', 'sku')
                        ->get(),
                    'low_stock' => Product::where('is_active', true)
                        ->where('stock_quantity', '>', 0)
                        ->where('stock_quantity', '<=', self::LOW_STOCK_THRESHOLD)
                        ->select('id', 'name', 'sku', 'stock_quantity')
                        ->get(),
                    'critical_stock' => Product::where('is_active', true)
                        ->where('stock_quantity', '>', 0)
                        ->where('stock_quantity', '<=', self::CRITICAL_STOCK_THRESHOLD)
                        ->select('id', 'name', 'sku', 'stock_quantity')
                        ->get()
                ],
                'movement_analysis' => [
                    'fast_moving' => $fastMovingProducts,
                    'slow_moving' => $slowMovingProducts
                ],
                'predictions' => $this->generateStockPredictions()
            ];
        });
    }

    /**
     * Generate stock level predictions based on sales trends
     *
     * @return array
     */
    private function generateStockPredictions(): array
    {
        $predictions = [];

        // Get products with recent sales data
        $productsWithSales = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.created_at', '>=', now()->subDays(30))
            ->where('orders.status', '!=', 'cancelled')
            ->select(
                'products.id',
                'products.name',
                'products.stock_quantity',
                DB::raw('AVG(order_items.quantity) as avg_daily_sales'),
                DB::raw('COUNT(DISTINCT DATE(orders.created_at)) as sales_days')
            )
            ->groupBy('products.id', 'products.name', 'products.stock_quantity')
            ->having('sales_days', '>=', 5) // At least 5 days of sales data
            ->get();

        foreach ($productsWithSales as $product) {
            $dailySalesRate = $product->avg_daily_sales;
            $currentStock = $product->stock_quantity;
            
            if ($dailySalesRate > 0) {
                $daysUntilOutOfStock = $currentStock / $dailySalesRate;
                
                $predictions[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'current_stock' => $currentStock,
                    'daily_sales_rate' => round($dailySalesRate, 2),
                    'days_until_out_of_stock' => round($daysUntilOutOfStock, 1),
                    'predicted_out_of_stock_date' => now()->addDays($daysUntilOutOfStock)->toDateString(),
                    'urgency' => $daysUntilOutOfStock <= 7 ? 'high' : ($daysUntilOutOfStock <= 14 ? 'medium' : 'low')
                ];
            }
        }

        // Sort by urgency (days until out of stock)
        usort($predictions, function ($a, $b) {
            return $a['days_until_out_of_stock'] <=> $b['days_until_out_of_stock'];
        });

        return array_slice($predictions, 0, 20); // Return top 20 predictions
    }

    /**
     * Check stock levels and trigger alerts
     *
     * @param Product $product
     */
    private function checkStockLevels(Product $product): void
    {
        if ($product->stock_quantity == 0) {
            event(new ProductOutOfStock($product));
        } elseif ($product->stock_quantity <= self::LOW_STOCK_THRESHOLD) {
            event(new LowStockAlert($product));
        }
    }

    /**
     * Log stock movement for audit trail
     *
     * @param Product $product
     * @param int $quantity
     * @param string $type
     * @param array $metadata
     */
    private function logStockMovement(Product $product, int $quantity, string $type, array $metadata = []): void
    {
        DB::table('stock_movements')->insert([
            'product_id' => $product->id,
            'quantity_change' => $quantity,
            'movement_type' => $type,
            'stock_before' => $product->stock_quantity - $quantity,
            'stock_after' => $product->stock_quantity,
            'metadata' => json_encode($metadata),
            'user_id' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Get stock movement history for a product
     *
     * @param Product $product
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getStockMovementHistory(Product $product, int $limit = 50)
    {
        return DB::table('stock_movements')
            ->where('product_id', $product->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($movement) {
                $movement->metadata = json_decode($movement->metadata, true);
                return $movement;
            });
    }

    /**
     * Validate stock consistency across the system
     *
     * @return array
     */
    public function validateStockConsistency(): array
    {
        $inconsistencies = [];

        // Check for negative stock
        $negativeStockProducts = Product::where('stock_quantity', '<', 0)->get();
        if ($negativeStockProducts->count() > 0) {
            $inconsistencies[] = [
                'type' => 'negative_stock',
                'message' => 'Products with negative stock found',
                'count' => $negativeStockProducts->count(),
                'products' => $negativeStockProducts->pluck('name', 'id')
            ];
        }

        // Check for reserved stock without corresponding orders
        $reservedOrders = Order::where('stock_reserved', true)
            ->whereNotIn('status', ['pending', 'processing'])
            ->get();
            
        if ($reservedOrders->count() > 0) {
            $inconsistencies[] = [
                'type' => 'invalid_reservations',
                'message' => 'Stock reserved for non-pending orders',
                'count' => $reservedOrders->count(),
                'orders' => $reservedOrders->pluck('id')
            ];
        }

        return [
            'is_consistent' => empty($inconsistencies),
            'inconsistencies' => $inconsistencies,
            'checked_at' => now()->toISOString()
        ];
    }
}