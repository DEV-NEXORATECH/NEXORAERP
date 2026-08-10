<?php

namespace App\Http\Controllers;

use App\Http\Traits\AppliesListFilters;
use App\Http\Traits\LoadsErpData;
use App\Models\StockLocation;
use App\Models\Warehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StockLocationController extends Controller
{
    use LoadsErpData, AppliesListFilters;

    public function index(Request $request): View
    {
        $locations = $this->applyListFilters(StockLocation::with('warehouse:id,code,name')->orderBy('code'), $request, ['name', 'code'])->paginate(15)->withQueryString();
        return view('erp.stock-locations.index', compact('locations'));
    }

    public function create(): View
    {
        $warehouses = Warehouse::orderBy('name')->get(['id', 'code', 'name']);
        return view('erp.stock-locations.create', compact('warehouses'));
    }

    public function edit(StockLocation $location): View
    {
        $warehouses = Warehouse::orderBy('name')->get(['id', 'code', 'name']);
        return view('erp.stock-locations.edit', compact('location', 'warehouses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $location = StockLocation::create($this->validated($request));
        $this->audit('created', $location, 'Lokasi stok dibuat');

        return redirect()->route('stock-locations.index')->with('status', 'Lokasi stok berhasil ditambahkan.');
    }

    public function update(Request $request, StockLocation $location): RedirectResponse
    {
        $old = $location->toArray();
        $location->update($this->validated($request, $location));
        $this->audit('updated', $location, 'Lokasi stok diupdate', $old, $location->fresh()->toArray());

        return redirect()->route('stock-locations.index')->with('status', 'Lokasi stok berhasil diupdate.');
    }

    public function destroy(StockLocation $location): RedirectResponse
    {
        $this->audit('deleted', $location, 'Lokasi stok dihapus', $location->toArray());
        $location->delete();

        return redirect()->route('stock-locations.index')->with('status', 'Lokasi stok berhasil dihapus.');
    }

    private function validated(Request $request, ?StockLocation $location = null): array
    {
        $data = $request->validate([
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'code' => ['required', 'max:50', 'regex:/^[a-zA-Z0-9_.-]+$/'],
            'name' => ['required', 'max:255'],
            'type' => ['required', Rule::in(['internal', 'input', 'output', 'transit'])],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
