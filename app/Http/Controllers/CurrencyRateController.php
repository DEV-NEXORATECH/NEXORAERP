<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\CurrencyRate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CurrencyRateController extends Controller
{
    use LoadsErpData;

    public function index(): View
    {
        $rates = CurrencyRate::latest('rate_date')->paginate(20);
        return view('erp.currency-rates.index', compact('rates'));
    }

    public function create(): View
    {
        return view('erp.currency-rates.create');
    }

    public function edit(CurrencyRate $rate): View
    {
        return view('erp.currency-rates.edit', compact('rate'));
    }

    public function store(Request $request): RedirectResponse
    {
        $row = CurrencyRate::create($request->validate([
            'from_currency' => ['required', 'max:3'],
            'to_currency' => ['required', 'max:3'],
            'rate' => ['required', 'numeric', 'min:0'],
            'rate_date' => ['required', 'date'],
            'source' => ['nullable', 'max:100'],
        ]));

        $this->audit('created', $row, 'Currency rate dibuat');

        return redirect()->route('currency-rates.index')->with('status', 'Currency rate berhasil ditambahkan.');
    }

    public function update(Request $request, CurrencyRate $rate): RedirectResponse
    {
        $old = $rate->toArray();
        $rate->update($request->validate([
            'from_currency' => ['required', 'max:3'],
            'to_currency' => ['required', 'max:3'],
            'rate' => ['required', 'numeric', 'min:0'],
            'rate_date' => ['required', 'date'],
            'source' => ['nullable', 'max:100'],
        ]));

        $this->audit('updated', $rate, 'Currency rate diedit', $old, $rate->fresh()->toArray());

        return redirect()->route('currency-rates.index')->with('status', 'Currency rate berhasil diupdate.');
    }

    public function destroy(CurrencyRate $rate): RedirectResponse
    {
        $this->audit('deleted', $rate, 'Currency rate dihapus', $rate->toArray());
        $rate->delete();

        return redirect()->route('currency-rates.index')->with('status', 'Currency rate berhasil dihapus.');
    }
}
