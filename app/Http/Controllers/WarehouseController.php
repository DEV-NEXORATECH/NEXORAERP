<?php

namespace App\Http\Controllers;

use App\Http\Traits\AppliesListFilters;
use App\Http\Traits\LoadsErpData;
use App\Models\Warehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class WarehouseController extends Controller
{
    use LoadsErpData, AppliesListFilters;

    public function index(Request $request): View
    {
        $warehouses = $this->applyListFilters(Warehouse::withCount('locations')->orderBy('code'), $request, ['name', 'code', 'address'])->paginate(8)->withQueryString();
        return view('erp.warehouses.index', compact('warehouses'));
    }

    public function create(): View
    {
        return view('erp.warehouses.create');
    }

    public function edit(Warehouse $warehouse): View
    {
        return view('erp.warehouses.edit', compact('warehouse'));
    }

    public function store(Request $request): RedirectResponse
    {
        $warehouse = Warehouse::create($this->validated($request));
        $this->audit('created', $warehouse, 'Gudang dibuat');

        return redirect()->route('warehouses.index')->with('status', 'Gudang berhasil ditambahkan.');
    }

    public function update(Request $request, Warehouse $warehouse): RedirectResponse
    {
        $old = $warehouse->toArray();
        $warehouse->update($this->validated($request, $warehouse));
        $this->audit('updated', $warehouse, 'Gudang diupdate', $old, $warehouse->fresh()->toArray());

        return redirect()->route('warehouses.index')->with('status', 'Gudang berhasil diupdate.');
    }

    public function destroy(Warehouse $warehouse): RedirectResponse
    {
        $this->audit('deleted', $warehouse, 'Gudang dihapus', $warehouse->toArray());
        $warehouse->delete();

        return redirect()->route('warehouses.index')->with('status', 'Gudang berhasil dihapus.');
    }

    private function validated(Request $request, ?Warehouse $warehouse = null): array
    {
        $data = $request->validate([
            'code' => ['required', 'max:50', 'regex:/^[a-zA-Z0-9_.-]+$/', Rule::unique('warehouses', 'code')->ignore($warehouse?->id)],
            'name' => ['required', 'max:255'],
            'address' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}

