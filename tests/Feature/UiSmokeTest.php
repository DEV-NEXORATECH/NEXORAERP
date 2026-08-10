<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class UiSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_ui_pages_without_parameters_do_not_error(): void
    {
        $this->seed();

        $admin = User::where('role', 'admin')->firstOrFail();

        $skipNames = [
            'backup.download',
            'login',
            'login.store',
            'logout',
            'password.change.store',
            'password.forgot',
            'password.forgot.store',
            'register',
            'register.store',
            'sanctum.csrf-cookie',
            'storage.local',
            'storage.local.upload',
        ];

        $routes = collect(Route::getRoutes())
            ->filter(fn ($route) => in_array('GET', $route->methods(), true))
            ->filter(fn ($route) => $route->getName())
            ->filter(fn ($route) => ! str_starts_with($route->uri(), 'api/'))
            ->filter(fn ($route) => ! str_contains($route->uri(), '{'))
            ->reject(fn ($route) => in_array($route->getName(), $skipNames, true))
            ->sortBy(fn ($route) => $route->uri());

        $failures = [];

        foreach ($routes as $route) {
            $response = $this->actingAs($admin)->get(route($route->getName()));

            if ($response->getStatusCode() >= 500) {
                $failures[] = $route->getName().' ['.$route->uri().'] returned '.$response->getStatusCode();
            }
        }

        $this->assertSame([], $failures);
    }

    public function test_literal_route_references_are_registered(): void
    {
        $registered = collect(Route::getRoutes())
            ->mapWithKeys(fn ($route) => [$route->getName() => true])
            ->filter();

        $files = collect();

        foreach ([resource_path('views'), app_path('Http/Controllers')] as $directory) {
            $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory));

            foreach ($iterator as $file) {
                if ($file->isFile() && in_array($file->getExtension(), ['php'], true)) {
                    $files->push($file->getPathname());
                }
            }
        }

        $files->push(base_path('routes/web.php'));

        $missing = [];
        $pattern = "/(?<!->)route\\(\\s*['\\\"]([^'\\\"]+)['\\\"]/";

        foreach ($files as $file) {
            preg_match_all($pattern, file_get_contents($file), $matches);

            foreach ($matches[1] as $routeName) {
                if (! isset($registered[$routeName])) {
                    $missing[] = str_replace(base_path().DIRECTORY_SEPARATOR, '', $file).': '.$routeName;
                }
            }
        }

        $this->assertSame([], array_values(array_unique($missing)));
    }
}
