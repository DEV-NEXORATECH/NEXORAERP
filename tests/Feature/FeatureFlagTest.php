<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Company;
use App\Models\FeatureFlag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeatureFlagTest extends TestCase
{
    use RefreshDatabase;

    private Company $company;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = Company::factory()->create([
            'code' => 'FF-001',
            'name' => 'Feature Flag Co',
            'access_type' => 'internal',
            'is_active' => true,
        ]);

        $this->admin = User::factory()->create([
            'company_id' => $this->company->id,
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);
    }

    public function test_admin_can_open_feature_flags_page(): void
    {
        FeatureFlag::create(['key' => 'sales.orders', 'label' => 'Sales Orders', 'module' => 'sales', 'is_enabled' => true, 'sort_order' => 0]);

        $this->actingAs($this->admin)
            ->get(route('feature-flags.index'))
            ->assertOk()
            ->assertSee('Sales Orders');
    }

    public function test_admin_can_toggle_feature_flag(): void
    {
        $flag = FeatureFlag::create(['key' => 'sales.orders', 'label' => 'Sales Orders', 'module' => 'sales', 'is_enabled' => true, 'sort_order' => 0]);

        $this->actingAs($this->admin)
            ->post(route('feature-flags.toggle'), ['key' => $flag->key, 'is_enabled' => 0])
            ->assertRedirect();

        $this->assertFalse($flag->fresh()->is_enabled);
        $this->assertTrue(FeatureFlag::isEnabled('sales.orders') === false);
        $this->assertDatabaseHas('audit_logs', ['action' => 'updated']);
    }

    public function test_admin_can_create_and_edit_and_delete_feature_flag(): void
    {
        $this->actingAs($this->admin)
            ->post(route('feature-flags.store'), [
                'key' => 'inventory.products',
                'label' => 'Products',
                'module' => 'inventory',
                'description' => 'Master produk.',
                'is_enabled' => 1,
            ])
            ->assertRedirect(route('feature-flags.index'));

        $flag = FeatureFlag::where('key', 'inventory.products')->first();
        $this->assertNotNull($flag);
        $this->assertDatabaseHas('audit_logs', ['action' => 'created']);

        $this->actingAs($this->admin)
            ->put(route('feature-flags.update', $flag), [
                'key' => 'inventory.products',
                'label' => 'Master Produk',
                'module' => 'inventory',
                'description' => 'Updated.',
                'is_enabled' => 1,
            ])
            ->assertRedirect(route('feature-flags.index'));

        $this->assertEquals('Master Produk', $flag->fresh()->label);

        $this->actingAs($this->admin)
            ->delete(route('feature-flags.destroy', $flag))
            ->assertRedirect(route('feature-flags.index'));

        $this->assertNull($flag->fresh());
    }

    public function test_feature_middleware_blocks_disabled_feature(): void
    {
        FeatureFlag::create(['key' => 'accounting.cashflows', 'label' => 'Cashflows', 'module' => 'accounting', 'is_enabled' => false, 'sort_order' => 0]);

        $this->actingAs($this->admin)
            ->get(route('cashflows.index-page'))
            ->assertForbidden();
    }

    public function test_api_returns_403_when_feature_disabled(): void
    {
        FeatureFlag::create(['key' => 'inventory.products', 'label' => 'Products', 'module' => 'inventory', 'is_enabled' => false, 'sort_order' => 0]);
        FeatureFlag::flushCache();

        $token = $this->postJson('/api/v1/auth/login', [
            'email' => $this->admin->email,
            'password' => 'password',
        ])->json('data.token');

        $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/v1/products')
            ->assertStatus(403)
            ->assertJson(['message' => 'Fitur tidak aktif atau sedang dinonaktifkan.']);
    }

    public function test_api_feature_flag_management_requires_admin(): void
    {
        $sales = User::factory()->create([
            'company_id' => $this->company->id,
            'role' => 'sales',
            'password' => bcrypt('password'),
        ]);

        $token = $this->postJson('/api/v1/auth/login', [
            'email' => $sales->email,
            'password' => 'password',
        ])->json('data.token');

        $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/v1/feature-flags')
            ->assertForbidden();

        $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/v1/feature-flags/toggle', ['key' => 'sales.orders', 'is_enabled' => false])
            ->assertForbidden();
    }

    public function test_api_can_list_and_toggle_feature_flags(): void
    {
        FeatureFlag::create(['key' => 'sales.orders', 'label' => 'Sales Orders', 'module' => 'sales', 'is_enabled' => true, 'sort_order' => 0]);

        $token = $this->postJson('/api/v1/auth/login', [
            'email' => $this->admin->email,
            'password' => 'password',
        ])->json('data.token');

        $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/v1/feature-flags')
            ->assertOk()
            ->assertJsonPath('data.sales.0.key', 'sales.orders');

        $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/v1/feature-flags/toggle', ['key' => 'sales.orders', 'is_enabled' => false])
            ->assertOk();

        $this->assertFalse(FeatureFlag::where('key', 'sales.orders')->first()->is_enabled);
    }

    public function test_disabled_module_filters_admin_menus(): void
    {
        FeatureFlag::create(['key' => 'purchase.orders', 'label' => 'Purchase Orders', 'module' => 'purchase', 'is_enabled' => true, 'sort_order' => 0]);

        $this->actingAs($this->admin)
            ->get(route('modules.index'))
            ->assertOk()
            ->assertSee('Procurement');

        FeatureFlag::where('key', 'purchase.orders')->update(['is_enabled' => false]);
        FeatureFlag::flushCache();

        $this->actingAs($this->admin)
            ->get(route('modules.index'))
            ->assertOk();
    }

    public function test_audit_logs_created_for_feature_flag_actions(): void
    {
        FeatureFlag::create(['key' => 'sales.orders', 'label' => 'Sales Orders', 'module' => 'sales', 'is_enabled' => true, 'sort_order' => 0]);

        $flag = FeatureFlag::where('key', 'sales.orders')->first();

        $this->actingAs($this->admin)->post(route('feature-flags.toggle'), ['key' => $flag->key, 'is_enabled' => 0]);

        $log = AuditLog::latest('id')->first();
        $this->assertNotNull($log);
        $this->assertEquals('updated', $log->action);
        $this->assertEquals($flag->id, $log->auditable_id);
        $this->assertEquals('FeatureFlag', $log->auditable_type);
    }
}
