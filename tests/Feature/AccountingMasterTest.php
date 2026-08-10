<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Currency;
use App\Models\FiscalPeriod;
use App\Models\PaymentTerm;
use App\Models\AccountingJournal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountingMasterTest extends TestCase
{
    use RefreshDatabase;

    private Company $company;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = Company::factory()->create([
            'code' => 'ACC-001',
            'name' => 'Accounting Co',
            'access_type' => 'internal',
            'is_active' => true,
        ]);

        $this->admin = User::factory()->create([
            'company_id' => $this->company->id,
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);
    }

    public function test_admin_can_open_accounting_journals_pages(): void
    {
        AccountingJournal::create(['code' => 'JSL', 'name' => 'Sales Journal', 'type' => 'sales', 'is_active' => true]);

        $this->actingAs($this->admin)
            ->get(route('accounting-journals.index'))
            ->assertOk()
            ->assertSee('Sales Journal');
    }

    public function test_admin_can_crud_accounting_journal(): void
    {
        $this->actingAs($this->admin)
            ->post(route('accounting-journals.store'), [
                'code' => 'JBN',
                'name' => 'Bank Journal',
                'type' => 'bank',
                'is_active' => 1,
            ])
            ->assertRedirect(route('accounting-journals.index'));

        $journal = AccountingJournal::where('code', 'JBN')->first();
        $this->assertNotNull($journal);

        $this->actingAs($this->admin)
            ->put(route('accounting-journals.update', $journal), [
                'code' => 'JBN',
                'name' => 'Bank Journal Updated',
                'type' => 'bank',
                'is_active' => 1,
            ])
            ->assertRedirect(route('accounting-journals.index'));

        $this->assertEquals('Bank Journal Updated', $journal->fresh()->name);

        $this->actingAs($this->admin)
            ->delete(route('accounting-journals.destroy', $journal))
            ->assertRedirect(route('accounting-journals.index'));

        $this->assertNull($journal->fresh());
    }

    public function test_admin_can_open_currencies_and_payment_terms_pages(): void
    {
        Currency::create(['code' => 'IDR', 'name' => 'Indonesian Rupiah', 'symbol' => 'Rp', 'rate' => 1, 'is_active' => true]);
        PaymentTerm::create(['code' => 'NET30', 'name' => 'Net 30 Hari', 'due_days' => 30, 'due_type' => 'day', 'is_active' => true]);

        $this->actingAs($this->admin)
            ->get(route('currencies.index'))
            ->assertOk()
            ->assertSee('Indonesian Rupiah');

        $this->actingAs($this->admin)
            ->get(route('payment-terms.index'))
            ->assertOk()
            ->assertSee('Net 30 Hari');
    }

    public function test_admin_can_crud_fiscal_period_and_toggle(): void
    {
        $this->actingAs($this->admin)
            ->post(route('fiscal-periods.store'), [
                'code' => '2026-01',
                'name' => '2026 - Bulan 01',
                'start_date' => '2026-01-01',
                'end_date' => '2026-01-31',
                'status' => 'open',
            ])
            ->assertRedirect(route('fiscal-periods.index'));

        $period = FiscalPeriod::where('code', '2026-01')->first();
        $this->assertNotNull($period);

        $this->actingAs($this->admin)
            ->patch(route('fiscal-periods.toggle', $period))
            ->assertRedirect();

        $this->assertEquals('closed', $period->fresh()->status);
    }

    public function test_api_can_crud_accounting_masters(): void
    {
        $token = $this->postJson('/api/v1/auth/login', [
            'email' => $this->admin->email,
            'password' => 'password',
        ])->json('data.token');
        $headers = ['Authorization' => "Bearer $token"];

        // Currency
        $this->withHeaders($headers)
            ->postJson('/api/v1/currencies', [
                'code' => 'USD',
                'name' => 'US Dollar',
                'symbol' => '$',
                'rate' => 16500,
                'is_active' => true,
            ])
            ->assertStatus(201);

        $this->withHeaders($headers)
            ->getJson('/api/v1/currencies?search=USD')
            ->assertOk()
            ->assertJsonPath('data.0.code', 'USD');

        // Payment term
        $this->withHeaders($headers)
            ->postJson('/api/v1/payment-terms', [
                'code' => 'NET14',
                'name' => 'Net 14 Hari',
                'due_days' => 14,
                'due_type' => 'day',
                'is_active' => true,
            ])
            ->assertStatus(201);

        // Accounting journal
        $this->withHeaders($headers)
            ->postJson('/api/v1/accounting-journals', [
                'code' => 'JOP',
                'name' => 'Opening Balance Journal',
                'type' => 'opening',
                'is_active' => true,
            ])
            ->assertStatus(201);

        // Fiscal period
        $this->withHeaders($headers)
            ->postJson('/api/v1/fiscal-periods', [
                'code' => '2026-02',
                'name' => '2026 - Bulan 02',
                'start_date' => '2026-02-01',
                'end_date' => '2026-02-28',
                'status' => 'open',
            ])
            ->assertStatus(201);

        $this->withHeaders($headers)
            ->getJson('/api/v1/fiscal-periods')
            ->assertOk()
            ->assertJsonCount(1, 'data');

        $this->withHeaders($headers)
            ->getJson('/api/v1/accounting-journals')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }
}
