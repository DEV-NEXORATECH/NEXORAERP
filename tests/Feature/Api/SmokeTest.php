<?php

namespace Tests\Feature\Api;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SmokeTest extends TestCase
{
    use RefreshDatabase;

    private Company $company;
    private User $user;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = Company::factory()->create([
            'code' => 'TEST-001',
            'name' => 'Test Company',
            'access_type' => 'internal',
            'is_active' => true,
        ]);

        $this->user = User::factory()->create([
            'company_id' => $this->company->id,
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);
    }

    public function test_login_returns_token(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['token', 'user']]);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $this->user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401);
    }

    public function test_me_endpoint(): void
    {
        $token = $this->getToken();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/v1/auth/me');

        $response->assertStatus(200);
        $response->assertJsonPath('data.user.email', $this->user->email);
    }

    public function test_dashboard(): void
    {
        $token = $this->getToken();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/v1/dashboard');

        $response->assertStatus(200);
    }

    public function test_crud_departments(): void
    {
        $token = $this->getToken();
        $headers = ['Authorization' => "Bearer $token"];

        // List (empty)
        $this->withHeaders($headers)->getJson('/api/v1/departments')
            ->assertStatus(200)
            ->assertJsonCount(0, 'data');

        // Create
        $this->withHeaders($headers)->postJson('/api/v1/departments', [
            'name' => 'Engineering',
        ])->assertStatus(201);

        // List (1 item)
        $this->withHeaders($headers)->getJson('/api/v1/departments')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data');

        // Read
        $this->withHeaders($headers)->getJson('/api/v1/departments/1')
            ->assertStatus(200)
            ->assertJsonPath('data.name', 'Engineering');
    }

    public function test_protected_routes_reject_unauthenticated(): void
    {
        $this->getJson('/api/v1/dashboard')->assertStatus(401);
        $this->getJson('/api/v1/departments')->assertStatus(401);
        $this->getJson('/api/v1/projects')->assertStatus(401);
        $this->getJson('/api/v1/users')->assertStatus(401);
    }

    public function test_logout_succeeds(): void
    {
        $token = $this->getToken();

        $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/v1/auth/logout')
            ->assertStatus(200);
    }

    private function getToken(): string
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        return $response->json('data.token');
    }
}
