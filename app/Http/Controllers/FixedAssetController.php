<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\FixedAsset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FixedAssetController extends Controller
{
    use LoadsErpData;

    public function index(): View
    {
        $assets = FixedAsset::latest()->paginate(20);
        return view('erp.fixed-assets.index', compact('assets'));
    }

    public function create(): View
    {
        return view('erp.fixed-assets.create');
    }

    public function edit(FixedAsset $asset): View
    {
        return view('erp.fixed-assets.edit', compact('asset'));
    }

    public function store(Request $request): RedirectResponse
    {
        $row = FixedAsset::create($request->validate([
            'name' => ['required', 'max:255'],
            'asset_code' => ['nullable', 'max:100'],
            'category' => ['nullable', 'max:100'],
            'purchase_date' => ['nullable', 'date'],
            'purchase_cost' => ['required', 'numeric', 'min:0'],
            'residual_value' => ['nullable', 'numeric', 'min:0'],
            'useful_life_years' => ['nullable', 'integer', 'min:1'],
            'depreciation_method' => ['nullable', Rule::in(['straight_line', 'declining'])],
            'monthly_depreciation' => ['nullable', 'numeric', 'min:0'],
            'accumulated_depreciation' => ['nullable', 'numeric', 'min:0'],
            'book_value' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['active', 'disposed', 'sold'])],
            'notes' => ['nullable'],
        ]));

        $this->audit('created', $row, 'Fixed asset dibuat');

        return redirect()->route('fixed-assets.index')->with('status', 'Fixed asset berhasil ditambahkan.');
    }

    public function update(Request $request, FixedAsset $asset): RedirectResponse
    {
        $old = $asset->toArray();
        $asset->update($request->validate([
            'name' => ['required', 'max:255'],
            'asset_code' => ['nullable', 'max:100'],
            'category' => ['nullable', 'max:100'],
            'purchase_date' => ['nullable', 'date'],
            'purchase_cost' => ['required', 'numeric', 'min:0'],
            'residual_value' => ['nullable', 'numeric', 'min:0'],
            'useful_life_years' => ['nullable', 'integer', 'min:1'],
            'depreciation_method' => ['nullable', Rule::in(['straight_line', 'declining'])],
            'monthly_depreciation' => ['nullable', 'numeric', 'min:0'],
            'accumulated_depreciation' => ['nullable', 'numeric', 'min:0'],
            'book_value' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['active', 'disposed', 'sold'])],
            'notes' => ['nullable'],
        ]));

        $this->audit('updated', $asset, 'Fixed asset diedit', $old, $asset->fresh()->toArray());

        return redirect()->route('fixed-assets.index')->with('status', 'Fixed asset berhasil diupdate.');
    }

    public function destroy(FixedAsset $asset): RedirectResponse
    {
        $this->audit('deleted', $asset, 'Fixed asset dihapus', $asset->toArray());
        $asset->delete();

        return redirect()->route('fixed-assets.index')->with('status', 'Fixed asset berhasil dihapus.');
    }
}
