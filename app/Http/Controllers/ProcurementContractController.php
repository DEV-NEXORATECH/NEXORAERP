<?php

namespace App\Http\Controllers;

use App\Http\Traits\AppliesListFilters;
use App\Http\Traits\LoadsErpData;
use App\Models\ProcurementContract;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProcurementContractController extends Controller
{
    use LoadsErpData, AppliesListFilters;

    public function index(Request $request): View
    {
        $contracts = $this->applyListFilters(
            ProcurementContract::with('vendor')->latest(),
            $request,
            ['contract_number', 'title']
        )->paginate(20)->withQueryString();

        if ($request->filled('vendor_id')) {
            $contracts->where('vendor_id', $request->input('vendor_id'));
        }

        $vendors = Vendor::orderBy('name')->get(['id', 'name']);
        return view('erp.procurement-contracts.index', compact('contracts', 'vendors'));
    }

    public function create(): View
    {
        $allVendors = Vendor::orderBy('name')->get(['id', 'name']);
        return view('erp.procurement-contracts.create', compact('allVendors'));
    }

    public function edit(ProcurementContract $contract): View
    {
        $allVendors = Vendor::orderBy('name')->get(['id', 'name']);
        return view('erp.procurement-contracts.edit', compact('contract', 'allVendors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'vendor_id'              => ['nullable', 'exists:vendors,id'],
            'title'                  => ['required', 'max:255'],
            'start_date'             => ['nullable', 'date'],
            'end_date'               => ['nullable', 'date', 'after_or_equal:start_date'],
            'renewal_reminder_date'  => ['nullable', 'date'],
            'amount'                 => ['required', 'numeric', 'min:0'],
            'status'                 => ['required', Rule::in(['draft', 'active', 'expired', 'terminated'])],
        ]);

        $row = ProcurementContract::create($data + ['contract_number' => $this->nextNumber('VCTR-NX', ProcurementContract::withTrashed()->count() + 1)]);
        $this->audit('created', $row, 'Procurement contract dibuat');

        return redirect()->route('procurement-contracts.index')->with('status', 'Procurement contract berhasil dicatat.');
    }

    public function update(Request $request, ProcurementContract $contract): RedirectResponse
    {
        $old = $contract->toArray();
        $contract->update($request->validate([
            'vendor_id'              => ['nullable', 'exists:vendors,id'],
            'title'                  => ['required', 'max:255'],
            'start_date'             => ['nullable', 'date'],
            'end_date'               => ['nullable', 'date', 'after_or_equal:start_date'],
            'renewal_reminder_date'  => ['nullable', 'date'],
            'amount'                 => ['required', 'numeric', 'min:0'],
            'status'                 => ['required', Rule::in(['draft', 'active', 'expired', 'terminated'])],
        ]));
        $this->audit('updated', $contract, 'Procurement contract diedit', $old, $contract->fresh()->toArray());

        return redirect()->route('procurement-contracts.index')->with('status', 'Procurement contract berhasil diupdate.');
    }

    public function destroy(ProcurementContract $contract): RedirectResponse
    {
        $this->audit('deleted', $contract, 'Procurement contract dihapus', $contract->toArray());
        $contract->delete();

        return redirect()->route('procurement-contracts.index')->with('status', 'Procurement contract berhasil dihapus.');
    }
}
