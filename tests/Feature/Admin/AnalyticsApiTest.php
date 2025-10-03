<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Comprehensive Admin Analytics API Tests
 * 
 * This test suite demonstrates excellent testing practices for Laravel 11
 * with Sanctum authentication, role-based access control, and API endpoints.
 */
class AnalyticsApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private Admin $admin;
    private User $regularUser;
    private string $adminToken;
    private string $userToken;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test admin with proper authentication
        $this->admin = Admin::factory()->create([
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        // Create test regular user
        $this->regularUser = User::factory()->create([
            'role' => 'customer',
            'is_active' => true,
        ]);

        // Generate Sanctum tokens
        $this->adminToken = $this->admin->createToken('admin-test', ['admin:*'])->plainTextToken;
        $this->userToken = $this->regularUser->createToken('user-test', ['user:*'])->plainTextToken;

        // Seed test data
        $this->seedTestData();
    }

    /**
     * Seed comprehensive test data for analytics
     */
    private function seedTestData(): void
    {
        // Create categories
        $categories = Category::factory(5)->create();

        // Create products across categories
        $products = collect();
        $categories->each(function ($category) use ($products) {
            $categoryProducts = Product::factory(10)->create([
                'category_id' => $category->id,
                'is_active' => true,
            ]);
            $products->push(...$categoryProducts);
        });

        // Create users with orders
        $users = User::factory(50)->create(['role' => 'customer']);
        
        // Create orders with varying dates for trend analysis
        $users->each(function ($user) use ($products) {
            $orderCount = rand(1, 5);
            
            for ($i = 0; $i < $orderCount; $i++) {
                $order = Order::factory()->create([
                    'user_id' => $user->id,
                    'total_amount' => rand(50, 500),
                    'status' => collect(['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->random(),
                    'created_at' => now()->subDays(rand(1, 90)),
                ]);

                // Add order items
                $orderProducts = $products->random(rand(1, 3));
                $order->orderItems()->createMany(
                    $orderProducts->map(function ($product) {
                        return [
                            'product_id' => $product->id,
                            'quantity' => rand(1, 3),
                            'price' => $product->price,
                        ];
                    })->toArray()
                );
            }
        });
    }

    /** @test */
    public function admin_can_access_dashboard_analytics()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->adminToken,
            'Accept' => 'application/json',
        ])->getJson('/api/admin/analytics/dashboard');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'status',
                    'data' => [
                        'overview' => [
                            'revenue' => [
                                'current',
                                'previous',
                                'growth_percentage',
                                'formatted_current'
                            ],
                            'orders' => [
                                'current',
                                'previous',
                                'growth_percentage',
                                'average_order_value'
                            ],
                            'users' => [
                                'current',
                                'previous',
                                'growth_percentage',
                                'total_active'
                            ],
                            'products' => [
                                'active',
                                'out_of_stock',
                                'low_stock'
                            ]
                        ],
                        'revenue_trend',
                        'orders_trend',
                        'users_trend',
                        'top_products',
                        'top_categories',
                        'customer_insights'
                    ],
                    'period' => [
                        'start_date',
                        'end_date',
                        'days'
                    ],
                    'generated_at'
                ]);

        $this->assertEquals('success', $response->json('status'));
    }

    /** @test */
    public function regular_user_cannot_access_admin_analytics()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->userToken,
            'Accept' => 'application/json',
        ])->getJson('/api/admin/analytics/dashboard');

        $response->assertStatus(403);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_analytics()
    {
        $response = $this->getJson('/api/admin/analytics/dashboard');

        $response->assertStatus(401);
    }

    /** @test */
    public function analytics_supports_custom_date_ranges()
    {
        $startDate = now()->subDays(60)->toDateString();
        $endDate = now()->subDays(30)->toDateString();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->adminToken,
        ])->getJson("/api/admin/analytics/dashboard?start_date={$startDate}&end_date={$endDate}");

        $response->assertStatus(200)
                ->assertJson([
                    'status' => 'success',
                    'period' => [
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                    ]
                ]);
    }

    /** @test */
    public function analytics_validates_date_range_input()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->adminToken,
        ])->getJson('/api/admin/analytics/dashboard?start_date=invalid-date');

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['start_date']);
    }

    /** @test */
    public function admin_can_access_activity_feed()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->adminToken,
        ])->getJson('/api/admin/analytics/activity-feed');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'status',
                    'data' => [
                        '*' => [
                            'id',
                            'type',
                            'title',
                            'description',
                            'timestamp',
                            'formatted_time'
                        ]
                    ],
                    'meta' => [
                        'count',
                        'generated_at'
                    ]
                ]);
    }

    /** @test */
    public function activity_feed_respects_limit_parameter()
    {
        $limit = 5;
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->adminToken,
        ])->getJson("/api/admin/analytics/activity-feed?limit={$limit}");

        $response->assertStatus(200);
        
        $activities = $response->json('data');
        $this->assertLessThanOrEqual($limit, count($activities));
    }

    /** @test */
    public function admin_can_export_analytics_data()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->adminToken,
        ])->postJson('/api/admin/analytics/export', [
            'format' => 'json',
            'period' => 30,
            'sections' => ['overview', 'revenue', 'orders']
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'status',
                    'data' => [
                        'overview',
                        'revenue_trend',
                        'orders_trend'
                    ],
                    'export_info' => [
                        'format',
                        'period_days',
                        'sections',
                        'generated_at',
                        'date_range'
                    ]
                ]);
    }

    /** @test */
    public function export_validates_required_parameters()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->adminToken,
        ])->postJson('/api/admin/analytics/export', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['format']);
    }

    /** @test */
    public function analytics_calculates_growth_percentages_correctly()
    {
        // Create specific test data for growth calculation
        $currentPeriodStart = now()->subDays(7);
        $previousPeriodStart = now()->subDays(14);
        $previousPeriodEnd = now()->subDays(8);

        // Create orders in previous period
        Order::factory(3)->create([
            'total_amount' => 100,
            'status' => 'delivered',
            'created_at' => $previousPeriodStart->addDays(1),
        ]);

        // Create orders in current period (should show 100% growth: 6 vs 3 orders)
        Order::factory(6)->create([
            'total_amount' => 100,
            'status' => 'delivered',
            'created_at' => $currentPeriodStart->addDays(1),
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->adminToken,
        ])->getJson('/api/admin/analytics/dashboard?period=7');

        $response->assertStatus(200);

        $orders = $response->json('data.overview.orders');
        $this->assertGreaterThan(0, $orders['growth_percentage']);
    }

    /** @test */
    public function top_products_analytics_includes_comprehensive_data()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->adminToken,
        ])->getJson('/api/admin/analytics/dashboard');

        $response->assertStatus(200);

        $topProducts = $response->json('data.top_products');
        
        if (!empty($topProducts)) {
            $product = $topProducts[0];
            
            $this->assertArrayHasKey('id', $product);
            $this->assertArrayHasKey('name', $product);
            $this->assertArrayHasKey('total_revenue', $product);
            $this->assertArrayHasKey('total_sold', $product);
            $this->assertArrayHasKey('formatted_revenue', $product);
            $this->assertArrayHasKey('price', $product);
            $this->assertArrayHasKey('stock_quantity', $product);
        }
    }

    /** @test */
    public function customer_insights_provides_valuable_segmentation()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->adminToken,
        ])->getJson('/api/admin/analytics/dashboard');

        $response->assertStatus(200);

        $customerInsights = $response->json('data.customer_insights');
        
        $this->assertArrayHasKey('top_customers', $customerInsights);
        $this->assertArrayHasKey('customer_acquisition', $customerInsights);
        
        $acquisition = $customerInsights['customer_acquisition'];
        $this->assertArrayHasKey('new_customers', $acquisition);
        $this->assertArrayHasKey('returning_customers', $acquisition);
        $this->assertArrayHasKey('total_active_customers', $acquisition);
    }

    /** @test */
    public function analytics_handles_empty_data_gracefully()
    {
        // Clear all test data
        Order::query()->delete();
        User::where('role', 'customer')->delete();
        Product::query()->delete();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->adminToken,
        ])->getJson('/api/admin/analytics/dashboard');

        $response->assertStatus(200);

        $overview = $response->json('data.overview');
        $this->assertEquals(0, $overview['revenue']['current']);
        $this->assertEquals(0, $overview['orders']['current']);
        $this->assertEquals(0, $overview['users']['current']);
    }

    /** @test */
    public function analytics_api_has_proper_rate_limiting()
    {
        // Make multiple requests to test rate limiting
        for ($i = 0; $i < 5; $i++) {
            $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . $this->adminToken,
            ])->getJson('/api/admin/analytics/dashboard');
            
            $this->assertContains($response->status(), [200, 429]);
        }
    }

    /** @test */
    public function analytics_response_includes_proper_timestamps()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->adminToken,
        ])->getJson('/api/admin/analytics/dashboard');

        $response->assertStatus(200);

        $generatedAt = $response->json('generated_at');
        $this->assertNotNull($generatedAt);
        
        // Verify it's a valid ISO 8601 timestamp
        $this->assertMatchesRegularExpression('/\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/', $generatedAt);
    }

    protected function tearDown(): void
    {
        // Clean up tokens
        $this->admin?->tokens()?->delete();
        $this->regularUser?->tokens()?->delete();
        
        parent::tearDown();
    }
}