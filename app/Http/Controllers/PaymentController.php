<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\Cashflow;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    use LoadsErpData;

    public function create(): View
    {
        $invoices     = Invoice::whereNotIn('status', ['paid', 'void'])->get();
        $bankAccounts = \App\Models\BankAccount::orderBy('name')->get();
        return view('erp.payments.create', compact('invoices', 'bankAccounts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data              = $request->validate($this->rules());
        $data['reference'] = $data['reference'] ?: $this->nextNumber('PAY-NX', Payment::withTrashed()->count() + 1);
        $data['proof_file_path'] = $this->storeUpload($request, 'proof_file', 'payments');
        unset($data['proof_file']);

        $invoice   = Invoice::with('project')->findOrFail($data['invoice_id']);
        $remaining = max(0, $invoice->amount - $invoice->paid_amount);

        if ($data['amount'] > $remaining) {
            return back()->withErrors(['amount' => 'Payment melebihi sisa invoice. Sisa: Rp ' . number_format($remaining, 0, ',', '.')])->withInput();
        }

        $cashflow = Cashflow::create([
            'project_id'       => $invoice->project_id,
            'type'             => 'income',
            'category'         => 'Invoice Payment',
            'bank_account_id'  => $data['bank_account_id'] ?? null,
            'cost_type'        => 'client_payment',
            'description'      => 'Payment invoice ' . $invoice->number,
            'amount'           => $data['amount'],
            'transaction_date' => $data['payment_date'],
        ]);

        $payment = Payment::create($data + ['cashflow_id' => $cashflow->id]);
        $this->refreshInvoiceStatus($invoice);
        $this->audit('created', $payment, 'Payment invoice dibuat dan masuk cashflow');

        return back()->with('status', 'Payment berhasil dicatat dan otomatis masuk cashflow income.');
    }

    public function destroy(Payment $payment): RedirectResponse
    {
        $invoice = $payment->invoice;
        $this->audit('deleted', $payment, 'Payment dihapus', $payment->toArray());

        if ($payment->cashflow_id) {
            Cashflow::find($payment->cashflow_id)?->delete();
        }

        $payment->delete();
        $this->refreshInvoiceStatus($invoice);

        return back()->with('status', 'Payment berhasil dihapus.');
    }

    private function refreshInvoiceStatus(Invoice $invoice): void
    {
        $paid   = $invoice->payments()->sum('amount');
        $status = $paid <= 0 ? $invoice->status : ($paid >= $invoice->amount ? 'paid' : 'partial');
        $invoice->update(['paid_amount' => $paid, 'status' => $status]);
    }

    private function rules(): array
    {
        return [
            'invoice_id'      => ['required', 'exists:invoices,id'],
            'bank_account_id' => ['nullable', 'exists:bank_accounts,id'],
            'amount'          => ['required', 'numeric', 'min:1'],
            'payment_date'    => ['required', 'date'],
            'method'          => ['required', 'max:50'],
            'reference'       => ['nullable', 'max:100'],
            'proof_file'      => ['nullable', 'file', 'max:4096'],
        ];
    }
}
