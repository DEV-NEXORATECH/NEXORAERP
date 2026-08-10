<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Company;
use App\Models\FeatureFlag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeatureFlagFrontendTest extends TestCase
{
    use RefreshDatabase;

    private Company $company;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = Company::factory()->create([
            'code' => 'FF2-001',
            'name' => 'Frontend Co',
            'access_type' => 'internal',
            'is_active' => true,
        ]);

        $this->admin = User::factory()->create([
            'company_id' => $this->company->id,
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        FeatureFlag::flushCache();
    }

    private function flag(string $key, string $module, bool $enabled = true): FeatureFlag
    {
        return FeatureFlag::create([
            'key' => $key,
            'label' => ucwords(str_replace(['.', '_', '-'], ' ', $key)),
            'module' => $module,
            'is_enabled' => $enabled,
            'sort_order' => 0,
        ]);
    }

    private function sidebarNav(): string
    {
        $response = $this->actingAs($this->admin)->get(route('dashboard'));
        $response->assertOk();
        preg_match('/<nav class="side-nav">(.*?)<\/nav>/s', $response->getContent(), $m);

        return $m[1] ?? '';
    }

    public function test_sidebar_hides_gated_links_when_flags_disabled(): void
    {
        $this->flag('platform.settings', 'platform');
        $this->flag('cms.sections', 'cms');
        $this->flag('reports.profit-loss', 'reports');

        $nav = $this->sidebarNav();
        $this->assertStringContainsString('Settings Admin', $nav);
        $this->assertStringContainsString('Report & Analyst', $nav);
        $this->assertStringContainsString('>CMS<', $nav);

        FeatureFlag::where('key', 'platform.settings')->update(['is_enabled' => false]);
        FeatureFlag::flushCache();

        $nav = $this->sidebarNav();
        $this->assertStringNotContainsString('Settings Admin', $nav);
        $this->assertStringContainsString('Report & Analyst', $nav);
        $this->assertStringContainsString('>CMS<', $nav);

        FeatureFlag::where('key', 'cms.sections')->update(['is_enabled' => false]);
        FeatureFlag::flushCache();

        $nav = $this->sidebarNav();
        $this->assertStringNotContainsString('Settings Admin', $nav);
        $this->assertStringNotContainsString('>CMS<', $nav);
        $this->assertStringContainsString('Report & Analyst', $nav);

        FeatureFlag::where('key', 'reports.profit-loss')->update(['is_enabled' => false]);
        FeatureFlag::flushCache();

        $nav = $this->sidebarNav();
        $this->assertStringNotContainsString('Settings Admin', $nav);
        $this->assertStringNotContainsString('>CMS<', $nav);
        $this->assertStringNotContainsString('Report & Analyst', $nav);
        $this->assertStringContainsString('Dashboard', $nav);
        $this->assertStringContainsString('Module', $nav);
    }

    public function test_feature_flags_page_renders_safe_toggle_forms(): void
    {
        $flag = FeatureFlag::create([
            'key' => 'sales.orders',
            'label' => 'Sales <script>alert(1)</script>',
            'module' => 'sales',
            'description' => 'Order penjualan.',
            'is_enabled' => true,
            'sort_order' => 0,
        ]);

        $this->actingAs($this->admin)
            ->get(route('feature-flags.index'))
            ->assertOk()
            ->assertSee('sales.orders')
            ->assertSee('Sales &lt;script&gt;alert(1)&lt;/script&gt;', false)
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertSee('_token', false)
            ->assertSee('feature-flags/toggle')
            ->assertSee('name="key"', false)
            ->assertSee('Klik untuk nonaktifkan');
    }

    public function test_full_toggle_process_web_and_api_end_to_end(): void
    {
        $this->flag('inventory.products', 'inventory');

        $this->actingAs($this->admin)
            ->get(route('modules.index'))
            ->assertOk()
            ->assertSee('Inventory Hub');

        $this->actingAs($this->admin)
            ->get(route('modules.show', 'inventory'))
            ->assertOk()
            ->assertSee('Products');

        $this->actingAs($this->admin)
            ->post(route('feature-flags.toggle'), ['key' => 'inventory.products', 'is_enabled' => 0])
            ->assertRedirect();

        $this->assertFalse(FeatureFlag::where('key', 'inventory.products')->first()->is_enabled);

        $this->actingAs($this->admin)
            ->get(route('modules.index'))
            ->assertOk()
            ->assertDontSee('Inventory Hub');

        $this->actingAs($this->admin)
            ->get(route('modules.show', 'inventory'))
            ->assertNotFound();

        $this->actingAs($this->admin)
            ->get(route('products.index'))
            ->assertForbidden();

        $token = $this->postJson('/api/v1/auth/login', [
            'email' => $this->admin->email,
            'password' => 'password',
        ])->json('data.token');

        $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/v1/products')
            ->assertStatus(403)
            ->assertJson(['message' => 'Fitur tidak aktif atau sedang dinonaktifkan.']);

        $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/v1/feature-flags/toggle', ['key' => 'inventory.products', 'is_enabled' => 1])
            ->assertOk();

        $this->assertTrue(FeatureFlag::where('key', 'inventory.products')->first()->is_enabled);

        $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/v1/products')
            ->assertOk();

        $this->actingAs($this->admin)
            ->get(route('modules.index'))
            ->assertOk()
            ->assertSee('Inventory Hub');

        $this->assertEquals(2, AuditLog::where('auditable_type', 'FeatureFlag')->count());
        $this->assertDatabaseHas('audit_logs', [
            'auditable_type' => 'FeatureFlag',
            'description' => 'Feature flag di-toggle',
        ]);
        $this->assertDatabaseHas('audit_logs', [
            'auditable_type' => 'FeatureFlag',
            'description' => 'Feature flag di-toggle (API)',
        ]);
    }

    public function test_api_flag_management_records_audit_log(): void
    {
        $this->flag('sales.orders', 'sales');

        $token = $this->postJson('/api/v1/auth/login', [
            'email' => $this->admin->email,
            'password' => 'password',
        ])->json('data.token');

        $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/v1/feature-flags', [
                'key' => 'crm.leads',
                'label' => 'Leads',
                'module' => 'crm',
                'is_enabled' => 1,
            ])
            ->assertCreated();

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->admin->id,
            'action' => 'created',
            'auditable_type' => 'FeatureFlag',
            'description' => 'Feature flag dibuat (API)',
        ]);
    }

    public function test_guest_cannot_access_feature_flags(): void
    {
        $this->get(route('feature-flags.index'))->assertForbidden();
        $this->post(route('feature-flags.toggle'), ['key' => 'sales.orders', 'is_enabled' => 0])
            ->assertForbidden();
    }

    public function test_non_admin_cannot_toggle_feature_flags_web(): void
    {
        $this->flag('sales.orders', 'sales');

        $sales = User::factory()->create([
            'company_id' => $this->company->id,
            'role' => 'sales',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($sales)
            ->get(route('feature-flags.index'))
            ->assertForbidden();

        $this->actingAs($sales)
            ->post(route('feature-flags.toggle'), ['key' => 'sales.orders', 'is_enabled' => 0])
            ->assertForbidden();

        $this->assertTrue(FeatureFlag::where('key', 'sales.orders')->first()->is_enabled);
    }
}
