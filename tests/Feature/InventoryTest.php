<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\StockAdjustment;
use App\Models\StockLocation;
use App\Models\StockMovement;
use App\Models\UnitOfMeasure;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryTest extends TestCase
{
    use RefreshDatabase;

    private Company $company;
    private User $admin;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = Company::factory()->create([
            'code' => 'ACC-001', 'name' => 'Inventory Co',
            'access_type' => 'internal', 'is_active' => true,
        ]);

        $this->admin = User::factory()->create([
            'company_id' => $this->company->id, 'role' => 'admin', 'password' => bcrypt('password'),
        ]);

        $category = ProductCategory::create(['company_id' => $this->company->id, 'code' => 'CAT-ELC', 'name' => 'Elektronik', 'is_active' => true]);
        $unit = UnitOfMeasure::create(['company_id' => $this->company->id, 'code' => 'PCS', 'name' => 'Pieces', 'category' => 'unit', 'is_active' => true]);
        $warehouse = Warehouse::create(['company_id' => $this->company->id, 'code' => 'WH-MAIN', 'name' => 'Main Warehouse', 'is_active' => true]);
        StockLocation::create(['company_id' => $this->company->id, 'warehouse_id' => $warehouse->id, 'code' => 'STK', 'name' => 'Stock Room', 'type' => 'internal', 'is_active' => true]);

        $this->product = Product::create([
            'company_id' => $this->company->id, 'category_id' => $category->id, 'unit_id' => $unit->id,
            'code' => 'PRD-001', 'name' => 'Laptop', 'type' => 'product', 'cost' => 15000000, 'price' => 17500000,
            'min_stock' => 2, 'is_active' => true,
        ]);

        StockMovement::create([
            'company_id' => $this->company->id, 'product_id' => $this->product->id, 'warehouse_id' => $warehouse->id,
            'type' => 'in', 'quantity' => 10, 'reference' => 'INIT', 'movement_date' => '2026-08-01', 'is_posted' => true,
        ]);
    }

    public function test_admin_can_open_inventory_pages(): void
    {
        $this->actingAs($this->admin)->get(route('products.index'))->assertOk()->assertSee('Laptop');
        $this->actingAs($this->admin)->get(route('product-categories.index'))->assertOk()->assertSee('Elektronik');
        $this->actingAs($this->admin)->get(route('product-units.index'))->assertOk()->assertSee('Pieces');
        $this->actingAs($this->admin)->get(route('warehouses.index'))->assertOk()->assertSee('Main Warehouse');
        $this->actingAs($this->admin)->get(route('stock-locations.index'))->assertOk()->assertSee('Stock Room');
        $this->actingAs($this->admin)->get(route('stock-movements.index'))->assertOk()->assertSee('Laptop');
        $this->actingAs($this->admin)->get(route('stock-adjustments.index'))->assertOk();
    }

    public function test_admin_can_create_product_and_record_stock_movement(): void
    {
        $this->actingAs($this->admin)
            ->post(route('products.store'), [
                'code' => 'PRD-002', 'name' => 'Monitor', 'type' => 'product',
                'category_id' => 1, 'unit_id' => 1, 'cost' => 2500000, 'price' => 3200000, 'min_stock' => 3, 'is_active' => 1,
            ])
            ->assertRedirect(route('products.index'));

        $monitor = Product::where('code', 'PRD-002')->first();
        $this->assertNotNull($monitor);

        $this->actingAs($this->admin)
            ->post(route('stock-movements.store'), [
                'product_id' => $this->product->id, 'type' => 'out', 'quantity' => 3,
                'movement_date' => '2026-08-05', 'unit_price' => 17500000,
            ])
            ->assertRedirect(route('stock-movements.index'));

        $this->assertEquals(7, $this->product->stockOnHand());
        $this->assertTrue($this->product->fresh()->stockOnHand() == 7);
    }

    public function test_admin_can_create_and_post_stock_adjustment(): void
    {
        $this->actingAs($this->admin)
            ->post(route('stock-adjustments.store'), [
                'product_id' => $this->product->id, 'actual_quantity' => 5,
                'reason' => 'Opname fisik', 'adjustment_date' => '2026-08-06',
            ])
            ->assertRedirect(route('stock-adjustments.index'));

        $adjustment = StockAdjustment::where('reason', 'Opname fisik')->first();
        $this->assertNotNull($adjustment);
        $this->assertEquals(10, (float) $adjustment->expected_quantity);
        $this->assertEquals(-5, $adjustment->deltaQuantity());

        $this->actingAs($this->admin)
            ->post(route('stock-adjustments.post', $adjustment))
            ->assertRedirect();

        $adjustment->refresh();
        $this->assertEquals('posted', $adjustment->status);
        $this->assertEquals(5, $this->product->fresh()->stockOnHand());

        $movement = StockMovement::where('source_type', 'adjustment')->where('source_id', $adjustment->id)->first();
        $this->assertNotNull($movement);
        $this->assertEquals('out', $movement->type);
        $this->assertEquals(5, (float) $movement->quantity);
    }

    public function test_api_inventory_crud_and_stock(): void
    {
        $token = $this->postJson('/api/v1/auth/login', ['email' => $this->admin->email, 'password' => 'password'])->json('data.token');
        $headers = ['Authorization' => "Bearer $token"];

        $this->withHeaders($headers)
            ->postJson('/api/v1/product-categories', ['code' => 'CAT-ATK', 'name' => 'Alat Tulis', 'is_active' => true])
            ->assertStatus(201);

        $this->withHeaders($headers)
            ->postJson('/api/v1/products', [
                'code' => 'PRD-API', 'name' => 'API Product', 'type' => 'product',
                'category_id' => 1, 'unit_id' => 1, 'cost' => 1000, 'price' => 2000, 'is_active' => true,
            ])
            ->assertStatus(201);

        $this->withHeaders($headers)
            ->getJson('/api/v1/products?search=API')
            ->assertOk()
            ->assertJsonPath('data.0.code', 'PRD-API');

        $this->withHeaders($headers)
            ->postJson('/api/v1/stock-movements', [
                'product_id' => $this->product->id, 'type' => 'in', 'quantity' => 2,
                'movement_date' => '2026-08-05',
            ])
            ->assertStatus(201);

        $this->withHeaders($headers)
            ->getJson('/api/v1/products-stock')
            ->assertOk()
            ->assertJsonCount(2, 'data');

        $this->withHeaders($headers)
            ->postJson('/api/v1/stock-adjustments', [
                'product_id' => $this->product->id, 'actual_quantity' => 4,
                'reason' => 'API opname', 'adjustment_date' => '2026-08-07',
            ])
            ->assertStatus(201)
            ->assertJsonPath('data.status', 'draft');

        $adjustment = StockAdjustment::where('reason', 'API opname')->first();

        $this->withHeaders($headers)
            ->postJson("/api/v1/stock-adjustments/{$adjustment->id}/post")
            ->assertOk()
            ->assertJsonPath('data.status', 'posted');

        $this->assertEquals(4, $this->product->fresh()->stockOnHand());
    }
}
