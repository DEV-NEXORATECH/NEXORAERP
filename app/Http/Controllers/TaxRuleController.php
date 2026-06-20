<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Http\Traits\AppliesListFilters;
use App\Models\TaxRule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TaxRuleController extends Controller
{
    use LoadsErpData, AppliesListFilters;

    public function index(Request $request): View
    {
        $taxRules = $this->applyListFilters(
            TaxRule::orderBy('tax_type'),
            $request,
            ['name']
        )->paginate(20)->withQueryString();

        if ($request->filled('is_active')) {
            $taxRules->where('is_active', $request->boolean('is_active'));
        }

        return view('erp.tax-rules.index', compact('taxRules'));
    }

    public function create(): View
    {
        return view('erp.tax-rules.create');
    }

    public function edit(TaxRule $taxRule): View
    {
        return view('erp.tax-rules.edit', compact('taxRule'));
    }

    public function store(Request $request): RedirectResponse
    {
        $tax = TaxRule::create($request->validate([
            'name' => ['required', 'max:100', 'unique:tax_rules,name'],
            'tax_type' => ['required', Rule::in(['PPN', 'PPh 21', 'PPh 23', 'PPh 4(2)'])],
            'rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'direction' => ['required', Rule::in(['input', 'output', 'withholding'])],
            'is_active' => ['nullable', 'boolean'],
        ]) + ['is_active' => $request->boolean('is_active', true)]);

        $this->audit('created', $tax, 'Tax rule dibuat');

        return redirect()->route('tax-rules.index')->with('status', 'Tax rule berhasil ditambahkan.');
    }

    public function update(Request $request, TaxRule $taxRule): RedirectResponse
    {
        $old = $taxRule->toArray();
        $taxRule->update($request->validate([
            'name' => ['required', 'max:100', Rule::unique('tax_rules', 'name')->ignore($taxRule)],
            'tax_type' => ['required', Rule::in(['PPN', 'PPh 21', 'PPh 23', 'PPh 4(2)'])],
            'rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'direction' => ['required', Rule::in(['input', 'output', 'withholding'])],
            'is_active' => ['nullable', 'boolean'],
        ]) + ['is_active' => $request->boolean('is_active', true)]);

        $this->audit('updated', $taxRule, 'Tax rule diedit', $old, $taxRule->fresh()->toArray());

        return redirect()->route('tax-rules.index')->with('status', 'Tax rule berhasil diupdate.');
    }

    public function destroy(TaxRule $taxRule): RedirectResponse
    {
        $this->audit('deleted', $taxRule, 'Tax rule dihapus', $taxRule->toArray());
        $taxRule->delete();

        return redirect()->route('tax-rules.index')->with('status', 'Tax rule berhasil dihapus.');
    }
}
