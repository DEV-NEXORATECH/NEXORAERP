<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\Cashflow;
use App\Models\VendorBill;
use App\Models\VendorPayment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VendorPaymentController extends Controller
{
    use LoadsErpData;

    public function index(): View
    {
        $payments = VendorPayment::with(['vendorBill:id,bill_number,vendor_name,amount,paid_amount', 'bankAccount:id,name'])->latest()->paginate(20);
        $unpaidVendorBills = VendorBill::whereIn('status', ['unpaid', 'partial'])->orderBy('due_date')->get();
        $bankAccounts = \App\Models\BankAccount::orderBy('name')->get();
        return view('erp.vendor-payments.index', compact('payments', 'unpaidVendorBills', 'bankAccounts'));
    }

    public function create(): View
    {
        $unpaidVendorBills = VendorBill::whereIn('status', ['unpaid', 'partial'])->orderBy('due_date')->get();
        $bankAccounts = \App\Models\BankAccount::orderBy('name')->get();
        return view('erp.vendor-payments.create', compact('unpaidVendorBills', 'bankAccounts'));
    }

    public function edit(VendorPayment $vendorPayment): View
    {
        $unpaidVendorBills = VendorBill::whereIn('status', ['unpaid', 'partial'])->orderBy('due_date')->get();
        $bankAccounts = \App\Models\BankAccount::orderBy('name')->get();
        return view('erp.vendor-payments.edit', compact('vendorPayment', 'unpaidVendorBills', 'bankAccounts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'vendor_bill_id' => ['required', 'exists:vendor_bills,id'],
            'bank_account_id' => ['nullable', 'exists:bank_accounts,id'],
            'amount' => ['required', 'numeric', 'min:1'],
            'payment_date' => ['required', 'date'],
            'reference' => ['nullable', 'max:100'],
        ]);

        $payment = DB::transaction(function () use ($data) {
            $bill = VendorBill::lockForUpdate()->findOrFail($data['vendor_bill_id']);
            $cashflow = Cashflow::create([
                'project_id' => $bill->project_id,
                'type' => 'expense',
                'category' => 'Vendor Payment',
                'bank_account_id' => $data['bank_account_id'] ?? $bill->bank_account_id,
                'cost_type' => 'vendor',
                'vendor' => $bill->vendor_name,
                'description' => 'Pembayaran vendor bill ' . $bill->bill_number,
                'amount' => $data['amount'],
                'transaction_date' => $data['payment_date'],
            ]);
            $payment = VendorPayment::create($data + ['cashflow_id' => $cashflow->id]);
            $paid = $bill->paid_amount + $data['amount'];
            $bill->update([
                'paid_amount' => $paid,
                'status' => $paid >= $bill->amount ? 'paid' : 'partial',
            ]);
            return $payment;
        });

        $this->audit('created', $payment, 'Pembayaran vendor bill dibuat');
        return redirect()->route('vendor-payments.index')->with('status', 'Pembayaran vendor berhasil dicatat.');
    }

    public function update(Request $request, VendorPayment $vendorPayment): RedirectResponse
    {
        $old = $vendorPayment->toArray();
        $vendorPayment->update($request->validate([
            'vendor_bill_id' => ['required', 'exists:vendor_bills,id'],
            'bank_account_id' => ['nullable', 'exists:bank_accounts,id'],
            'amount' => ['required', 'numeric', 'min:1'],
            'payment_date' => ['required', 'date'],
            'reference' => ['nullable', 'max:100'],
        ]));

        $this->audit('updated', $vendorPayment, 'Vendor payment diedit', $old, $vendorPayment->fresh()->toArray());

        return redirect()->route('vendor-payments.index')->with('status', 'Vendor payment berhasil diupdate.');
    }

    public function destroy(VendorPayment $vendorPayment): RedirectResponse
    {
        $this->audit('deleted', $vendorPayment, 'Vendor payment dihapus', $vendorPayment->toArray());
        $vendorPayment->delete();

        return redirect()->route('vendor-payments.index')->with('status', 'Vendor payment berhasil dihapus.');
    }
}
