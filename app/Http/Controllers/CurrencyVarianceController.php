<?php

namespace App\Http\Controllers;

use App\Http\Traits\AppliesListFilters;
use App\Http\Traits\LoadsErpData;
use App\Models\CurrencyRate;
use App\Models\CurrencyVariance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CurrencyVarianceController extends Controller
{
    use LoadsErpData, AppliesListFilters;

    public function index(Request $request): View
    {
        $variances = $this->applyListFilters(
            CurrencyVariance::with('rate')->latest(),
            $request,
            ['notes', 'period']
        )->paginate(20)->withQueryString();
        return view('erp.currency-variances.index', compact('variances'));
    }

    public function create(): View
    {
        $rates = CurrencyRate::orderByDesc('rate_date')->get(['id', 'from_currency', 'to_currency', 'rate_date']);
        return view('erp.currency-variances.create', compact('rates'));
    }

    public function edit(CurrencyVariance $variance): View
    {
        $rates = CurrencyRate::orderByDesc('rate_date')->get(['id', 'from_currency', 'to_currency', 'rate_date']);
        return view('erp.currency-variances.edit', compact('variance', 'rates'));
    }

    public function store(Request $request): RedirectResponse
    {
        $row = CurrencyVariance::create($request->validate([
            'rate_id' => ['required', 'exists:currency_rates,id'],
            'variance_percent' => ['nullable', 'numeric'],
            'variance_amount' => ['nullable', 'numeric'],
            'period' => ['nullable', 'max:20'],
            'notes' => ['nullable'],
        ]));

        $this->audit('created', $row, 'Currency variance dibuat');

        return redirect()->route('currency-variances.index')->with('status', 'Currency variance berhasil ditambahkan.');
    }

    public function update(Request $request, CurrencyVariance $variance): RedirectResponse
    {
        $old = $variance->toArray();
        $variance->update($request->validate([
            'rate_id' => ['required', 'exists:currency_rates,id'],
            'variance_percent' => ['nullable', 'numeric'],
            'variance_amount' => ['nullable', 'numeric'],
            'period' => ['nullable', 'max:20'],
            'notes' => ['nullable'],
        ]));

        $this->audit('updated', $variance, 'Currency variance diedit', $old, $variance->fresh()->toArray());

        return redirect()->route('currency-variances.index')->with('status', 'Currency variance berhasil diupdate.');
    }

    public function destroy(CurrencyVariance $variance): RedirectResponse
    {
        $this->audit('deleted', $variance, 'Currency variance dihapus', $variance->toArray());
        $variance->delete();

        return redirect()->route('currency-variances.index')->with('status', 'Currency variance berhasil dihapus.');
    }
}
