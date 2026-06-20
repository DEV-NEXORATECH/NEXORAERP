<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\Budget;
use App\Models\Cashflow;
use App\Models\ChartAccount;
use App\Models\Invoice;
use App\Models\JournalEntry;
use App\Models\PaymentReminder;
use App\Models\RecurringBilling;
use App\Models\TaxRule;
use App\Models\VendorBill;
use App\Models\VendorPayment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FinanceSuiteController extends Controller
{
    use LoadsErpData;

    public function index(): View
    {
        $invoices = Invoice::with(['project:id,code'])->latest()->paginate(10, ['*'], 'ar_page')->withQueryString();
        $vendorBills = VendorBill::with(['project:id,code'])->latest()->paginate(10, ['*'], 'ap_page')->withQueryString();
        $recurrings = RecurringBilling::with('client:id,name')->latest()->paginate(8, ['*'], 'recurring_page')->withQueryString();
        $reminders = PaymentReminder::with('invoice:id,number,due_date')->latest()->paginate(8, ['*'], 'reminder_page')->withQueryString();
        $accounts = ChartAccount::with('parent:id,code,name')->orderBy('code')->paginate(12, ['*'], 'coa_page')->withQueryString();
        $journals = JournalEntry::with('lines.account:id,code,name')->latest('entry_date')->paginate(8, ['*'], 'journal_page')->withQueryString();
        $budgets = Budget::with(['project:id,code', 'account:id,code,name'])->latest()->paginate(8, ['*'], 'budget_page')->withQueryString();
        $taxRules = TaxRule::orderBy('tax_type')->paginate(8, ['*'], 'tax_page')->withQueryString();

        $bankAccounts = \App\Models\BankAccount::orderBy('name')->get();
        $clients = \App\Models\Client::orderBy('name')->get(['id', 'name']);
        $projects = \App\Models\Project::orderBy('code')->get(['id', 'code']);
        $unpaidInvoices = Invoice::whereNotIn('status', ['paid', 'void'])->orderBy('due_date')->get(['id', 'number', 'due_date']);
        $unpaidVendorBills = VendorBill::whereIn('status', ['unpaid', 'partial'])->orderBy('due_date')->get();
        $coaOptions = ChartAccount::where('is_active', true)->orderBy('code')->get(['id', 'code', 'name', 'type']);

        $cashflows = Cashflow::all();
        $cashSummary = $this->cashflowSummary($cashflows);
        $arOutstanding = Invoice::whereNotIn('status', ['paid', 'void'])->get()->sum(fn (Invoice $invoice) => $invoice->amount - $invoice->paid_amount);
        $apOutstanding = VendorBill::whereIn('status', ['unpaid', 'partial'])->get()->sum(fn (VendorBill $bill) => $bill->amount - $bill->paid_amount);
        $budgetTotal = Budget::sum('budget_amount');
        $forecastTotal = Budget::sum('forecast_amount');

        return view('erp.finance-suite.index', compact(
            'invoices',
            'vendorBills',
            'recurrings',
            'reminders',
            'accounts',
            'journals',
            'budgets',
            'taxRules',
            'bankAccounts',
            'clients',
            'projects',
            'unpaidInvoices',
            'unpaidVendorBills',
            'coaOptions',
            'cashSummary',
            'arOutstanding',
            'apOutstanding',
            'budgetTotal',
            'forecastTotal'
        ));
    }

    public function storeCoa(Request $request): RedirectResponse
    {
        $account = ChartAccount::create($request->validate([
            'code' => ['required', 'max:50', 'unique:chart_accounts,code'],
            'name' => ['required', 'max:255'],
            'type' => ['required', Rule::in(['asset', 'liability', 'equity', 'revenue', 'expense'])],
            'parent_id' => ['nullable', 'exists:chart_accounts,id'],
            'is_active' => ['nullable', 'boolean'],
        ]) + ['is_active' => $request->boolean('is_active', true)]);

        $this->audit('created', $account, 'Chart of account dibuat');

        return back()->with('status', 'CoA berhasil ditambahkan.');
    }

    public function storeJournal(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'entry_date' => ['required', 'date'],
            'reference' => ['nullable', 'max:100'],
            'memo' => ['nullable'],
            'lines' => ['required', 'array', 'min:2'],
            'lines.*.chart_account_id' => ['required', 'exists:chart_accounts,id'],
            'lines.*.debit' => ['nullable', 'numeric', 'min:0'],
            'lines.*.credit' => ['nullable', 'numeric', 'min:0'],
            'lines.*.description' => ['nullable', 'max:255'],
        ]);

        $debit = collect($data['lines'])->sum(fn ($line) => (float) ($line['debit'] ?? 0));
        $credit = collect($data['lines'])->sum(fn ($line) => (float) ($line['credit'] ?? 0));

        if ($debit <= 0 || round($debit, 2) !== round($credit, 2)) {
            return back()->withErrors(['journal' => 'Journal harus balance: total debit dan credit wajib sama.'])->withInput();
        }

        $journal = DB::transaction(function () use ($data) {
            $journal = JournalEntry::create([
                'number' => $this->nextNumber('JRN-NX', JournalEntry::count() + 1),
                'entry_date' => $data['entry_date'],
                'source' => 'manual',
                'reference' => $data['reference'] ?? null,
                'memo' => $data['memo'] ?? null,
            ]);

            foreach ($data['lines'] as $line) {
                $journal->lines()->create($line);
            }

            return $journal;
        });

        $this->audit('created', $journal, 'Journal entry dibuat');

        return back()->with('status', 'Journal entry berhasil dibuat.');
    }

    public function storeRecurring(Request $request): RedirectResponse
    {
        $billing = RecurringBilling::create($request->validate([
            'client_id' => ['nullable', 'exists:clients,id'],
            'name' => ['required', 'max:255'],
            'frequency' => ['required', Rule::in(['weekly', 'monthly', 'quarterly', 'yearly'])],
            'amount' => ['required', 'numeric', 'min:0'],
            'tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'next_invoice_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:next_invoice_date'],
            'status' => ['required', Rule::in(['active', 'paused', 'ended'])],
        ]));

        $this->audit('created', $billing, 'Recurring billing dibuat');

        return back()->with('status', 'Recurring billing berhasil ditambahkan.');
    }

    public function storeReminder(Request $request): RedirectResponse
    {
        $reminder = PaymentReminder::create($request->validate([
            'invoice_id' => ['required', 'exists:invoices,id'],
            'reminder_date' => ['required', 'date'],
            'channel' => ['required', Rule::in(['email', 'whatsapp', 'phone'])],
            'status' => ['required', Rule::in(['scheduled', 'sent', 'cancelled'])],
            'message' => ['nullable'],
        ]));

        $this->audit('created', $reminder, 'Payment reminder dibuat');

        return back()->with('status', 'Reminder pembayaran berhasil dijadwalkan.');
    }

    public function storeVendorBill(Request $request): RedirectResponse
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

        return back()->with('status', 'Vendor bill berhasil dicatat.');
    }

    public function storeVendorPayment(Request $request): RedirectResponse
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
                'description' => 'Pembayaran vendor bill '.$bill->bill_number,
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

        return back()->with('status', 'Pembayaran vendor berhasil dicatat dan masuk cashflow expense.');
    }

    public function storeBudget(Request $request): RedirectResponse
    {
        $budget = Budget::create($request->validate([
            'project_id' => ['nullable', 'exists:projects,id'],
            'chart_account_id' => ['nullable', 'exists:chart_accounts,id'],
            'period' => ['required', 'max:20'],
            'budget_amount' => ['required', 'numeric', 'min:0'],
            'forecast_amount' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable'],
        ]));

        $this->audit('created', $budget, 'Budget forecast dibuat');

        return back()->with('status', 'Budget & forecast berhasil disimpan.');
    }

    public function storeTaxRule(Request $request): RedirectResponse
    {
        $tax = TaxRule::create($request->validate([
            'name' => ['required', 'max:100', 'unique:tax_rules,name'],
            'tax_type' => ['required', Rule::in(['PPN', 'PPh 21', 'PPh 23', 'PPh 4(2)'])],
            'rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'direction' => ['required', Rule::in(['input', 'output', 'withholding'])],
            'is_active' => ['nullable', 'boolean'],
        ]) + ['is_active' => $request->boolean('is_active', true)]);

        $this->audit('created', $tax, 'Tax rule dibuat');

        return back()->with('status', 'Tax rule berhasil ditambahkan.');
    }
}
