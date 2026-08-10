<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\LoadsErpData;
use App\Models\FeatureFlag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FeatureFlagApiController extends Controller
{
    use LoadsErpData;
    public function index(Request $request): JsonResponse
    {
        $query = FeatureFlag::orderBy('module')->orderBy('sort_order');

        if ($request->filled('module')) {
            $query->where('module', $request->input('module'));
        }

        $flags = $query->get()->groupBy('module');

        return $this->respond($flags->toArray());
    }

    public function toggle(Request $request): JsonResponse
    {
        $data = $request->validate([
            'key' => ['required', 'string', Rule::exists('feature_flags', 'key')],
            'is_enabled' => ['required', 'boolean'],
        ]);

        $flag = FeatureFlag::where('key', $data['key'])->firstOrFail();
        $old = ['is_enabled' => $flag->is_enabled];
        $flag->update(['is_enabled' => (bool) $data['is_enabled']]);
        $this->audit('updated', $flag, 'Feature flag di-toggle (API)', $old, ['is_enabled' => $flag->is_enabled]);
        FeatureFlag::flushCache();

        return $this->respond($flag->fresh(), 'Feature flag updated.');
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'key' => ['required', 'string', 'max:100', 'regex:/^[a-z0-9_.-]+$/', Rule::unique('feature_flags', 'key')],
            'label' => ['required', 'string', 'max:255'],
            'module' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_enabled' => ['sometimes', 'boolean'],
            'sort_order' => ['sometimes', 'integer'],
        ]);

        $flag = FeatureFlag::create($data);
        $this->audit('created', $flag, 'Feature flag dibuat (API)');
        FeatureFlag::flushCache();

        return $this->respond($flag, 'Created successfully.', 201);
    }

    public function update(Request $request, FeatureFlag $flag): JsonResponse
    {
        $data = $request->validate([
            'key' => ['required', 'string', 'max:100', 'regex:/^[a-z0-9_.-]+$/', Rule::unique('feature_flags', 'key')->ignore($flag)],
            'label' => ['required', 'string', 'max:255'],
            'module' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_enabled' => ['sometimes', 'boolean'],
            'sort_order' => ['sometimes', 'integer'],
        ]);

        $old = $flag->toArray();
        $flag->update($data);
        $this->audit('updated', $flag, 'Feature flag diupdate (API)', $old, $flag->fresh()->toArray());
        FeatureFlag::flushCache();

        return $this->respond($flag->fresh(), 'Updated successfully.');
    }

    public function destroy(FeatureFlag $flag): JsonResponse
    {
        $this->audit('deleted', $flag, 'Feature flag dihapus (API)', $flag->toArray());
        $flag->delete();
        FeatureFlag::flushCache();

        return $this->respondDeleted();
    }
}
