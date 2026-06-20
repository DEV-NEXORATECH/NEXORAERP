<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function test_admin_can_open_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee('NEXORA ERP');
    }

    public function test_admin_can_open_split_module_pages(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        foreach ([
            '/projects',
            '/modules',
            '/modules/finance',
            '/modules/sales',
            '/approvals',
            '/projects/create',
            '/sales',
            '/sales-crm',
            '/sales/proposals/create',
            '/hr',
            '/hris',
            '/hr/salaries',
            '/finance',
            '/finance-suite',
            '/finance-advanced',
            '/procurement',
            '/finance/reimbursements',
            '/finance/cashflows',
            '/reports',
            '/admin',
            '/admin/users',
            '/admin/masters',
            '/admin/trash',
            '/admin/audit',
        ] as $path) {
            $this->actingAs($user)->get($path)->assertStatus(200);
        }
    }

    public function test_admin_can_open_custom_report_types(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        foreach ([
            'profit_loss',
            'balance_sheet',
            'cash_flow_statement',
            'project_profitability',
            'aging_ar',
            'aging_ap',
            'tax_summary',
            'budget_vs_actual',
            'transactions',
            'bank_reconciliation',
        ] as $report) {
            $this->actingAs($user)->get('/reports?report='.$report)->assertStatus(200);
        }
    }
}
