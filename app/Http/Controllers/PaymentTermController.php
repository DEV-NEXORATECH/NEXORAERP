<?php

namespace App\Http\Controllers;

use App\Http\Traits\AppliesListFilters;
use App\Http\Traits\LoadsErpData;
use App\Models\PaymentTerm;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PaymentTermController extends Controller
{
    use LoadsErpData, AppliesListFilters;

    public function index(Request $request): View
    {
        $terms = $this->applyListFilters(PaymentTerm::orderBy('due_days'), $request, ['name', 'code'])->paginate(15)->withQueryString();
        return view('erp.payment-terms.index', compact('terms'));
    }

    public function create(): View
    {
        return view('erp.payment-terms.create');
    }

    public function edit(PaymentTerm $term): View
    {
        return view('erp.payment-terms.edit', compact('term'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $term = PaymentTerm::create($data);
        $this->audit('created', $term, 'Payment term dibuat');

        return redirect()->route('payment-terms.index')->with('status', 'Payment term berhasil ditambahkan.');
    }

    public function update(Request $request, PaymentTerm $term): RedirectResponse
    {
        $data = $this->validated($request, $term);
        $old = $term->toArray();
        $term->update($data);
        $this->audit('updated', $term, 'Payment term diupdate', $old, $term->fresh()->toArray());

        return redirect()->route('payment-terms.index')->with('status', 'Payment term berhasil diupdate.');
    }

    public function destroy(PaymentTerm $term): RedirectResponse
    {
        $this->audit('deleted', $term, 'Payment term dihapus', $term->toArray());
        $term->delete();

        return redirect()->route('payment-terms.index')->with('status', 'Payment term berhasil dihapus.');
    }

    private function validated(Request $request, ?PaymentTerm $term = null): array
    {
        return $request->validate([
            'code' => ['required', 'max:50', 'regex:/^[a-zA-Z0-9_.-]+$/', Rule::unique('payment_terms', 'code')->ignore($term?->id)],
            'name' => ['required', 'max:255'],
            'due_days' => ['required', 'integer', 'min:0', 'max:3660'],
            'due_type' => ['required', Rule::in(['day', 'month'])],
            'is_active' => ['sometimes', 'boolean'],
        ]);
    }
}
