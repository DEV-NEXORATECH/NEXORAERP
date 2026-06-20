<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Http\Traits\AppliesListFilters;
use App\Models\VendorBill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VendorBillController extends Controller
{
    use LoadsErpData, AppliesListFilters;

    public function index(Request $request): View
    {
        $vendorBills = $this->applyListFilters(
            VendorBill::with(['project:id,code'])->latest(),
            $request,
            ['vendor_name', 'bill_number', 'notes']
        )->paginate(20)->withQueryString();
        $projects = \App\Models\Project::orderBy('code')->get(['id', 'code']);
        $bankAccounts = \App\Models\BankAccount::orderBy('name')->get();
        return view('erp.vendor-bills.index', compact('vendorBills', 'projects', 'bankAccounts'));
    }

    public function create(): View
    {
        $projects = \App\Models\Project::orderBy('code')->get(['id', 'code']);
        $bankAccounts = \App\Models\BankAccount::orderBy('name')->get();
        return view('erp.vendor-bills.create', compact('projects', 'bankAccounts'));
    }

    public function edit(VendorBill $vendorBill): View
    {
        $projects = \App\Models\Project::orderBy('code')->get(['id', 'code']);
        $bankAccounts = \App\Models\BankAccount::orderBy('name')->get();
        return view('erp.vendor-bills.edit', compact('vendorBill', 'projects', 'bankAccounts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'project_id' => ['nullable', 'exists:projects,id'],
            'bank_account_id' => ['nullable', 'exists:bank_accounts,id'],
            'vendor_name' => ['required', 'max:255'],
            'bill_number' => ['nullable', 'max:100', 'unique:vendor_bills,bill_number'],
            'bill_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:bill_date'],
            'amount' => ['required', 'numeric', 'min:0'],
            'tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'status' => ['required', Rule::in(['unpaid', 'partial', 'paid', 'void'])],
            'notes' => ['nullable'],
        ]);
        $data['bill_number'] = $data['bill_number'] ?: $this->nextNumber('BILL-NX', VendorBill::count() + 1);

        $bill = VendorBill::create($data);
        $this->audit('created', $bill, 'Vendor bill dibuat');

        return redirect()->route('vendor-bills.index')->with('status', 'Vendor bill berhasil dicatat.');
    }

    public function update(Request $request, VendorBill $vendorBill): RedirectResponse
    {
        $old = $vendorBill->toArray();
        $data = $request->validate([
            'project_id' => ['nullable', 'exists:projects,id'],
            'bank_account_id' => ['nullable', 'exists:bank_accounts,id'],
            'vendor_name' => ['required', 'max:255'],
            'bill_number' => ['nullable', 'max:100', Rule::unique('vendor_bills', 'bill_number')->ignore($vendorBill)],
            'bill_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:bill_date'],
            'amount' => ['required', 'numeric', 'min:0'],
            'tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'status' => ['required', Rule::in(['unpaid', 'partial', 'paid', 'void'])],
            'notes' => ['nullable'],
        ]);
        $data['bill_number'] = $data['bill_number'] ?: $this->nextNumber('BILL-NX', VendorBill::count() + 1);
        $vendorBill->update($data);

        $this->audit('updated', $vendorBill, 'Vendor bill diedit', $old, $vendorBill->fresh()->toArray());

        return redirect()->route('vendor-bills.index')->with('status', 'Vendor bill berhasil diupdate.');
    }

    public function destroy(VendorBill $vendorBill): RedirectResponse
    {
        $this->audit('deleted', $vendorBill, 'Vendor bill dihapus', $vendorBill->toArray());
        $vendorBill->delete();

        return redirect()->route('vendor-bills.index')->with('status', 'Vendor bill berhasil dihapus.');
    }
}
