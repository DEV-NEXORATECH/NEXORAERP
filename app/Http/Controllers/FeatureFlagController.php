<?php

namespace App\Http\Controllers;

use App\Http\Traits\AppliesListFilters;
use App\Http\Traits\LoadsErpData;
use App\Models\FeatureFlag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FeatureFlagController extends Controller
{
    use LoadsErpData, AppliesListFilters;

    public function index(): View
    {
        $flags = FeatureFlag::orderBy('sort_order')->get()->groupBy('module');
        $modules = config('nexora.feature_modules', [
            'platform' => 'Platform',
            'accounting' => 'Accounting',
            'sales' => 'Sales',
            'crm' => 'CRM',
            'purchase' => 'Purchase',
            'inventory' => 'Inventory',
            'contact' => 'Contact',
            'hr' => 'HR / HRIS',
            'reports' => 'Reports',
            'cms' => 'CMS',
        ]);
        $enabledCount = FeatureFlag::where('is_enabled', true)->count();
        $totalCount = FeatureFlag::count();

        return view('erp.feature-flags.index', compact('flags', 'modules', 'enabledCount', 'totalCount'));
    }

    public function toggle(Request $request): RedirectResponse
    {
        $request->validate([
            'key' => ['required', 'string', Rule::exists('feature_flags', 'key')],
            'is_enabled' => ['required', 'boolean'],
        ]);

        $flag = FeatureFlag::where('key', $request->input('key'))->firstOrFail();
        $old = ['is_enabled' => $flag->is_enabled];
        $flag->update(['is_enabled' => (bool) $request->input('is_enabled')]);
        $this->audit('updated', $flag, 'Feature flag di-toggle', $old, ['is_enabled' => $flag->is_enabled]);
        FeatureFlag::flushCache();

        return back()->with('status', "Fitur \"{$flag->label}\" " . ($flag->is_enabled ? 'diaktifkan' : 'dinonaktifkan') . '.');
    }

    public function create(): View
    {
        return view('erp.feature-flags.create');
    }

    public function store(Request $request): RedirectResponse
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
        $this->audit('created', $flag, 'Feature flag dibuat');
        FeatureFlag::flushCache();

        return redirect()->route('feature-flags.index')->with('status', 'Fitur baru berhasil ditambahkan.');
    }

    public function edit(FeatureFlag $flag): View
    {
        return view('erp.feature-flags.edit', compact('flag'));
    }

    public function update(Request $request, FeatureFlag $flag): RedirectResponse
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
        $this->audit('updated', $flag, 'Feature flag diupdate', $old, $flag->fresh()->toArray());
        FeatureFlag::flushCache();

        return redirect()->route('feature-flags.index')->with('status', 'Fitur berhasil diupdate.');
    }

    public function destroy(FeatureFlag $flag): RedirectResponse
    {
        $this->audit('deleted', $flag, 'Feature flag dihapus', $flag->toArray());
        $flag->delete();
        FeatureFlag::flushCache();

        return redirect()->route('feature-flags.index')->with('status', 'Fitur berhasil dihapus.');
    }
}
