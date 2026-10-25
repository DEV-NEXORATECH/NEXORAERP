<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardFullTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_10_dashboard_web_views_load_successfully(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $routes = [
            'dashboard',
            'dashboard.executive',
            'dashboard.sales',
            'dashboard.projects',
            'dashboard.hr',
            'dashboard.finance',
            'dashboard.procurement',
            'dashboard.inventory',
            'dashboard.cms',
            'dashboard.workspace',
        ];

        foreach ($routes as $routeName) {
            $response = $this->actingAs($user)->get(route($routeName));
            $response->assertStatus(200);
            $response->assertViewIs(match ($routeName) {
                'dashboard'             => 'erp.dashboard.index',
                'dashboard.executive'   => 'erp.dashboard.executive',
                'dashboard.sales'       => 'erp.dashboard.sales',
                'dashboard.projects'    => 'erp.dashboard.projects',
                'dashboard.hr'          => 'erp.dashboard.hr',
                'dashboard.finance'     => 'erp.dashboard.finance',
                'dashboard.procurement' => 'erp.dashboard.procurement',
                'dashboard.inventory'   => 'erp.dashboard.inventory',
                'dashboard.cms'         => 'erp.dashboard.cms',
                'dashboard.workspace'   => 'erp.dashboard.workspace',
            });
        }
    }

    public function test_all_10_dashboard_api_endpoints_return_valid_json(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $apiEndpoints = [
            '/api/v1/dashboard',
            '/api/v1/dashboard/executive',
            '/api/v1/dashboard/sales',
            '/api/v1/dashboard/projects',
            '/api/v1/dashboard/hr',
            '/api/v1/dashboard/finance',
            '/api/v1/dashboard/procurement',
            '/api/v1/dashboard/inventory',
            '/api/v1/dashboard/cms',
            '/api/v1/dashboard/workspace',
        ];

        foreach ($apiEndpoints as $endpoint) {
            $response = $this->actingAs($user)->getJson($endpoint);
            $response->assertStatus(200);
            $response->assertJsonStructure([
                'message',
                'data',
            ]);
        }
    }

    public function test_dashboard_period_filters_work(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        foreach (['today', '7_days', '30_days', 'this_month', 'last_month', 'this_quarter', 'this_year'] as $period) {
            $response = $this->actingAs($user)->get(route('dashboard', ['period' => $period]));
            $response->assertStatus(200);
        }
    }
}
