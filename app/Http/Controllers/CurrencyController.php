<?php

namespace App\Http\Controllers;

use App\Http\Traits\AppliesListFilters;
use App\Http\Traits\LoadsErpData;
use App\Models\Currency;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CurrencyController extends Controller
{
    use LoadsErpData, AppliesListFilters;

    public function index(Request $request): View
    {
        $currencies = $this->applyListFilters(Currency::orderBy('code'), $request, ['code', 'name'])->paginate(15)->withQueryString();
        return view('erp.currencies.index', compact('currencies'));
    }

    public function create(): View
    {
        return view('erp.currencies.create');
    }

    public function edit(Currency $currency): View
    {
        return view('erp.currencies.edit', compact('currency'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $currency = Currency::create($data);
        $this->audit('created', $currency, 'Mata uang dibuat');

        return redirect()->route('currencies.index')->with('status', 'Mata uang berhasil ditambahkan.');
    }

    public function update(Request $request, Currency $currency): RedirectResponse
    {
        $data = $this->validated($request, $currency);
        $old = $currency->toArray();
        $currency->update($data);
        $this->audit('updated', $currency, 'Mata uang diupdate', $old, $currency->fresh()->toArray());

        return redirect()->route('currencies.index')->with('status', 'Mata uang berhasil diupdate.');
    }

    public function destroy(Currency $currency): RedirectResponse
    {
        $this->audit('deleted', $currency, 'Mata uang dihapus', $currency->toArray());
        $currency->delete();

        return redirect()->route('currencies.index')->with('status', 'Mata uang berhasil dihapus.');
    }

    private function validated(Request $request, ?Currency $currency = null): array
    {
        return $request->validate([
            'code' => ['required', 'max:8', 'regex:/^[A-Za-z]{2,8}$/', Rule::unique('currencies', 'code')->ignore($currency?->id)],
            'name' => ['required', 'max:255'],
            'symbol' => ['nullable', 'max:8'],
            'rate' => ['required', 'numeric', 'min:0.000001'],
            'is_active' => ['sometimes', 'boolean'],
        ]);
    }
}
