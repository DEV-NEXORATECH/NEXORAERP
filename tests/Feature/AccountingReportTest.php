<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\AccountingJournal;
use App\Models\ChartAccount;
use App\Models\JournalEntry;
use App\Models\JournalLine;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountingReportTest extends TestCase
{
    use RefreshDatabase;

    private Company $company;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = Company::factory()->create([
            'code' => 'ACC-001', 'name' => 'Accounting Co',
            'access_type' => 'internal', 'is_active' => true,
        ]);

        $this->admin = User::factory()->create([
            'company_id' => $this->company->id, 'role' => 'admin', 'password' => bcrypt('password'),
        ]);

        $journal = AccountingJournal::create(['company_id' => $this->company->id, 'code' => 'JSL', 'name' => 'Sales Journal', 'type' => 'sales', 'is_active' => true]);
        $cash = ChartAccount::create(['company_id' => $this->company->id, 'code' => '1010', 'name' => 'Kas', 'type' => 'asset', 'is_active' => true]);
        $revenue = ChartAccount::create(['company_id' => $this->company->id, 'code' => '4010', 'name' => 'Pendapatan', 'type' => 'income', 'is_active' => true]);

        $entry = JournalEntry::create([
            'company_id' => $this->company->id, 'number' => 'JRNL-202608-0001', 'journal_id' => $journal->id,
            'entry_date' => '2026-08-01', 'source' => 'manual', 'memo' => 'Test entry',
            'is_posted' => true, 'posting_date' => '2026-08-01',
        ]);

        JournalLine::create(['company_id' => $this->company->id, 'journal_entry_id' => $entry->id, 'chart_account_id' => $cash->id, 'debit' => 1000000, 'credit' => 0, 'description' => 'Setoran']);
        JournalLine::create(['company_id' => $this->company->id, 'journal_entry_id' => $entry->id, 'chart_account_id' => $revenue->id, 'debit' => 0, 'credit' => 1000000, 'description' => 'Pendapatan']);
    }

    public function test_admin_can_open_trial_balance_report(): void
    {
        $this->actingAs($this->admin)
            ->get(route('reports.trial-balance'))
            ->assertOk()
            ->assertSee('Kas')
            ->assertSee('Pendapatan');
    }

    public function test_trial_balance_balances(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('reports.trial-balance'));

        $response->assertOk();

        $content = $response->getContent();
        $this->assertMatchesRegularExpression('/1\.000\.000/', $content);
    }

    public function test_admin_can_open_general_ledger_report(): void
    {
        $this->actingAs($this->admin)
            ->get(route('reports.general-ledger', ['chart_account_id' => 1]))
            ->assertOk()
            ->assertSee('Kas');
    }

    public function test_trial_balance_api(): void
    {
        $token = $this->postJson('/api/v1/auth/login', ['email' => $this->admin->email, 'password' => 'password'])->json('data.token');

        $this->withHeaders(['Authorization' => "Bearer $token"])
            ->getJson('/api/v1/reports/trial-balance')
            ->assertOk()
            ->assertJsonStructure(['data' => ['accounts', 'totals']])
            ->assertJsonPath('data.totals.debit', 1000000)
            ->assertJsonPath('data.totals.credit', 1000000);
    }
}
