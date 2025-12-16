<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class OrderApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Crear y autenticar un usuario para todos los tests
        $user = User::factory()->create();
        Sanctum::actingAs($user);
    }

    /**
     * Test listing all orders.
     */
    public function test_can_list_all_orders(): void
    {
        $order = Order::create([
            'customer_name' => 'Test Customer',
            'customer_email' => 'test@example.com',
            'total' => 100.00,
            'status' => 'pending',
        ]);

        $order->items()->create([
            'product_name' => 'Test Product',
            'quantity' => 2,
            'unit_price' => 50.00,
            'subtotal' => 100.00,
        ]);

        $response = $this->getJson('/api/orders');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'customer_name',
                        'customer_email',
                        'total',
                        'status',
                        'items',
                    ]
                ],
                'message'
            ]);
    }

    /**
     * Test creating an order successfully.
     */
    public function test_can_create_order(): void
    {
        $orderData = [
            'customer_name' => 'Juan Pérez',
            'customer_email' => 'juan@example.com',
            'items' => [
                [
                    'product_name' => 'Producto A',
                    'quantity' => 2,
                    'unit_price' => 50.00,
                ],
                [
                    'product_name' => 'Producto B',
                    'quantity' => 1,
                    'unit_price' => 30.00,
                ],
            ],
        ];

        $response = $this->postJson('/api/orders', $orderData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'customer_name',
                    'customer_email',
                    'total',
                    'status',
                    'items',
                ],
                'message'
            ])
            ->assertJsonPath('data.customer_name', 'Juan Pérez')
            ->assertJsonPath('data.total', '130.00')
            ->assertJsonPath('data.status', 'pending');

        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Juan Pérez',
            'customer_email' => 'juan@example.com',
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_name' => 'Producto A',
            'quantity' => 2,
        ]);
    }

    /**
     * Test validation errors when creating an order.
     */
    public function test_create_order_validation_errors(): void
    {
        $response = $this->postJson('/api/orders', []);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'customer_name',
                    'customer_email',
                    'items',
                ]
            ]);
    }

    /**
     * Test validation fails with invalid email.
     */
    public function test_create_order_invalid_email(): void
    {
        $orderData = [
            'customer_name' => 'Juan Pérez',
            'customer_email' => 'invalid-email',
            'items' => [
                [
                    'product_name' => 'Producto A',
                    'quantity' => 2,
                    'unit_price' => 50.00,
                ],
            ],
        ];

        $response = $this->postJson('/api/orders', $orderData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['customer_email']);
    }

    /**
     * Test validation fails with empty items.
     */
    public function test_create_order_empty_items(): void
    {
        $orderData = [
            'customer_name' => 'Juan Pérez',
            'customer_email' => 'juan@example.com',
            'items' => [],
        ];

        $response = $this->postJson('/api/orders', $orderData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['items']);
    }

    /**
     * Test getting a single order.
     */
    public function test_can_get_single_order(): void
    {
        $order = Order::create([
            'customer_name' => 'Test Customer',
            'customer_email' => 'test@example.com',
            'total' => 100.00,
            'status' => 'pending',
        ]);

        $order->items()->create([
            'product_name' => 'Test Product',
            'quantity' => 2,
            'unit_price' => 50.00,
            'subtotal' => 100.00,
        ]);

        $response = $this->getJson("/api/orders/{$order->id}");

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $order->id)
            ->assertJsonPath('data.customer_name', 'Test Customer');
    }

    /**
     * Test getting a non-existent order returns 404.
     */
    public function test_get_nonexistent_order_returns_404(): void
    {
        $response = $this->getJson('/api/orders/99999');

        $response->assertStatus(404)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Orden no encontrada');
    }

    /**
     * Test order total is calculated correctly.
     */
    public function test_order_total_calculated_correctly(): void
    {
        $orderData = [
            'customer_name' => 'Test Customer',
            'customer_email' => 'test@example.com',
            'items' => [
                [
                    'product_name' => 'Product A',
                    'quantity' => 3,
                    'unit_price' => 10.00,
                ],
                [
                    'product_name' => 'Product B',
                    'quantity' => 2,
                    'unit_price' => 25.50,
                ],
            ],
        ];

        $response = $this->postJson('/api/orders', $orderData);

        // Total should be (3 * 10) + (2 * 25.50) = 30 + 51 = 81
        $response->assertStatus(201)
            ->assertJsonPath('data.total', '81.00');
    }

    /**
     * Test unauthenticated request returns 401.
     */
    public function test_unauthenticated_request_returns_401(): void
    {
        // Clear authentication
        $this->app['auth']->forgetGuards();
        
        $response = $this->getJson('/api/orders');

        $response->assertStatus(401);
    }
}
