<?php

namespace App\Http\Middleware;

use App\Models\FeatureFlag;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FeatureCheck
{
    /**
     * Abort with 403 (or redirect for web) when the feature is disabled.
     * Feature key dapat diberikan sebagai parameter (feature:key) atau
     * otomatis di-resolve dari route name via config/nexora.php.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ?string $feature = null): Response
    {
        if ($feature === null) {
            $routeName = $request->route()?->getName();
            if ($routeName !== null) {
                $feature = config('nexora.feature_routes')[$routeName] ?? null;

                // API routes (api.products.index → products.index) resolve against
                // the same route→feature map used by the web routes.
                $candidate = str_starts_with($routeName, 'api.') ? substr($routeName, 4) : $routeName;
                if ($feature === null) {
                    $feature = config('nexora.feature_routes')[$candidate] ?? null;
                }

                // Action variants without an explicit mapping (e.g. products.show,
                // payments.index) inherit the feature of the resource's .index route.
                if ($feature === null) {
                    $pos = strrpos($candidate, '.');
                    $base = $pos !== false ? substr($candidate, 0, $pos) : null;
                    if ($base !== null) {
                        $feature = config('nexora.feature_routes')[$base.'.index'] ?? null;
                    }
                }
            }
        }

        if ($feature !== null && ! FeatureFlag::isEnabled($feature)) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Fitur tidak aktif atau sedang dinonaktifkan.'], 403);
            }

            abort(403, 'Fitur ini sedang dinonaktifkan.');
        }

        return $next($request);
    }
}
