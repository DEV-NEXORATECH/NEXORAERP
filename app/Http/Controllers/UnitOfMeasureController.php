<?php

namespace App\Http\Controllers;

use App\Http\Traits\AppliesListFilters;
use App\Http\Traits\LoadsErpData;
use App\Models\UnitOfMeasure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UnitOfMeasureController extends Controller
{
    use LoadsErpData, AppliesListFilters;

    public function index(Request $request): View
    {
        $units = $this->applyListFilters(UnitOfMeasure::withCount('products')->orderBy('code'), $request, ['name', 'code'])->paginate(8)->withQueryString();
        return view('erp.product-units.index', compact('units'));
    }

    public function create(): View
    {
        return view('erp.product-units.create');
    }

    public function edit(UnitOfMeasure $unit): View
    {
        return view('erp.product-units.edit', compact('unit'));
    }

    public function store(Request $request): RedirectResponse
    {
        $unit = UnitOfMeasure::create($this->validated($request));
        $this->audit('created', $unit, 'Satuan produk dibuat');

        return redirect()->route('product-units.index')->with('status', 'Satuan produk berhasil ditambahkan.');
    }

    public function update(Request $request, UnitOfMeasure $unit): RedirectResponse
    {
        $old = $unit->toArray();
        $unit->update($this->validated($request, $unit));
        $this->audit('updated', $unit, 'Satuan produk diupdate', $old, $unit->fresh()->toArray());

        return redirect()->route('product-units.index')->with('status', 'Satuan produk berhasil diupdate.');
    }

    public function destroy(UnitOfMeasure $unit): RedirectResponse
    {
        $this->audit('deleted', $unit, 'Satuan produk dihapus', $unit->toArray());
        $unit->delete();

        return redirect()->route('product-units.index')->with('status', 'Satuan produk berhasil dihapus.');
    }

    private function validated(Request $request, ?UnitOfMeasure $unit = null): array
    {
        $data = $request->validate([
            'code' => ['required', 'max:20', 'regex:/^[a-zA-Z0-9_-]+$/', Rule::unique('units_of_measure', 'code')->ignore($unit?->id)],
            'name' => ['required', 'max:255'],
            'category' => ['required', Rule::in(['unit', 'weight', 'volume'])],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}

