<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Company;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\StockMovement;
use App\Models\UnitOfMeasure;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderInventoryTest extends TestCase
{
    use RefreshDatabase;

    private Company $company;
    private User $admin;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = Company::factory()->create([
            'code' => 'ORD-001', 'name' => 'Order Co',
            'access_type' => 'internal', 'is_active' => true,
        ]);

        $this->admin = User::factory()->create([
            'company_id' => $this->company->id, 'role' => 'admin', 'password' => bcrypt('password'),
        ]);

        $category = ProductCategory::create(['company_id' => $this->company->id, 'code' => 'CAT-ELC', 'name' => 'Elektronik', 'is_active' => true]);
        $unit = UnitOfMeasure::create(['company_id' => $this->company->id, 'code' => 'PCS', 'name' => 'Pieces', 'category' => 'unit', 'is_active' => true]);

        $this->product = Product::create([
            'company_id' => $this->company->id, 'category_id' => $category->id, 'unit_id' => $unit->id,
            'code' => 'PRD-001', 'name' => 'Laptop', 'type' => 'product', 'cost' => 12000000, 'price' => 15000000,
            'min_stock' => 2, 'is_active' => true,
        ]);
    }

    public function test_admin_can_create_sales_order_with_items_and_confirm_reduces_stock(): void
    {
        Client::create(['company_id' => $this->company->id, 'name' => 'PT Pelanggan']);

        $this->actingAs($this->admin)
            ->post(route('sales-orders.store'), [
                'title' => 'SO Produk', 'order_date' => '2026-08-09', 'status' => 'confirmed',
                'items' => [
                    ['product_id' => $this->product->id, 'quantity' => 3, 'unit_price' => 15000000],
                ],
            ])
            ->assertRedirect(route('sales-orders.index'));

        $order = SalesOrder::where('title', 'SO Produk')->first();
        $this->assertNotNull($order);
        $this->assertSame(45000000, (int) round((float) $order->amount));

        $item = SalesOrderItem::where('sales_order_id', $order->id)->first();
        $this->assertNotNull($item);
        $this->assertEquals(3, (float) $item->quantity);
        $this->assertEquals(-3, $this->product->fresh()->stockOnHand());

        $movement = StockMovement::where('source_type', 'sales_order')->where('source_id', $order->id)->first();
        $this->assertNotNull($movement);
        $this->assertEquals('out', $movement->type);
        $this->assertEquals(3, (float) $movement->quantity);
        $this->assertTrue((bool) $movement->is_posted);
    }

    public function test_draft_sales_order_does_not_touch_stock(): void
    {
        $this->actingAs($this->admin)
            ->post(route('sales-orders.store'), [
                'title' => 'SO Draft', 'order_date' => '2026-08-09', 'status' => 'draft',
                'items' => [
                    ['product_id' => $this->product->id, 'quantity' => 5, 'unit_price' => 15000000],
                ],
            ])
            ->assertRedirect(route('sales-orders.index'));

        $order = SalesOrder::where('title', 'SO Draft')->first();
        $this->assertSame(0.0, (float) $this->product->fresh()->stockOnHand());
        $this->assertNull(StockMovement::where('source_type', 'sales_order')->where('source_id', $order->id)->first());
    }

    public function test_admin_can_create_purchase_order_with_items_and_receive_adds_stock(): void
    {
        Vendor::create(['company_id' => $this->company->id, 'name' => 'PT Vendor', 'status' => 'active']);

        $this->actingAs($this->admin)
            ->post(route('purchase-orders.store'), [
                'order_date' => '2026-08-09', 'status' => 'received',
                'items' => [
                    ['product_id' => $this->product->id, 'quantity' => 10, 'unit_price' => 12000000],
                ],
            ])
            ->assertRedirect(route('purchase-orders.index'));

        $order = PurchaseOrder::whereNotNull('id')->latest('id')->first();
        $this->assertNotNull($order);
        $this->assertSame(120000000, (int) round((float) $order->amount));

        $item = PurchaseOrderItem::where('purchase_order_id', $order->id)->first();
        $this->assertNotNull($item);
        $this->assertEquals(10, (float) $item->quantity);
        $this->assertEquals(10, $this->product->fresh()->stockOnHand());

        $movement = StockMovement::where('source_type', 'purchase_order')->where('source_id', $order->id)->first();
        $this->assertNotNull($movement);
        $this->assertEquals('in', $movement->type);
        $this->assertEquals(10, (float) $movement->quantity);
    }

    public function test_api_can_create_sales_order_with_items_and_post_stock(): void
    {
        Client::create(['company_id' => $this->company->id, 'name' => 'PT API']);

        $token = $this->postJson('/api/v1/auth/login', ['email' => $this->admin->email, 'password' => 'password'])->json('data.token');
        $headers = ['Authorization' => "Bearer $token"];

        $this->withHeaders($headers)
            ->postJson('/api/v1/sales-orders', [
                'title' => 'SO API', 'order_date' => '2026-08-09', 'status' => 'confirmed',
                'items' => [
                    ['product_id' => $this->product->id, 'quantity' => 2, 'unit_price' => 15000000],
                ],
            ])
            ->assertStatus(201)
            ->assertJsonPath('data.status', 'confirmed');

        $order = SalesOrder::where('title', 'SO API')->first();
        $this->assertNotNull($order);
        $this->assertSame(30000000, (int) round((float) $order->amount));
        $this->assertEquals(-2, $this->product->fresh()->stockOnHand());
        $this->assertNotNull(StockMovement::where('source_type', 'sales_order')->where('source_id', $order->id)->first());
    }

    public function test_purchase_order_items_visible_on_index(): void
    {
        Vendor::create(['company_id' => $this->company->id, 'name' => 'PT Vendor', 'status' => 'active']);

        $this->actingAs($this->admin)
            ->post(route('purchase-orders.store'), [
                'order_date' => '2026-08-09', 'status' => 'ordered',
                'items' => [
                    ['product_id' => $this->product->id, 'quantity' => 7, 'unit_price' => 12000000],
                ],
            ])
            ->assertRedirect(route('purchase-orders.index'));

        $this->actingAs($this->admin)
            ->get(route('purchase-orders.index'))
            ->assertOk()
            ->assertSee('Purchase Order');
    }
}
