@extends('layouts.erp', ['activePage' => 'finance-suite', 'pageTitle' => 'Finance Suite'])

@section('content')
<section class="grid cards section">
    <div class="stat-card"><div class="stat-card-label">AR Outstanding</div><div class="stat-card-metric bad">{{ $rp($arOutstanding) }}</div><div class="muted">Invoice belum lunas</div></div>
    <div class="stat-card"><div class="stat-card-label">AP Outstanding</div><div class="stat-card-metric bad">{{ $rp($apOutstanding) }}</div><div class="muted">Vendor bill belum dibayar</div></div>
    <div class="stat-card"><div class="stat-card-label">Cash Balance</div><div class="stat-card-metric {{ $cashSummary['balance'] >= 0 ? 'good' : 'bad' }}">{{ $rp($cashSummary['balance']) }}</div><div class="muted">Income - expense</div></div>
    <div class="stat-card"><div class="stat-card-label">Budget / Forecast</div><div class="stat-card-metric">{{ $rp($budgetTotal) }}</div><div class="muted">Forecast: {{ $rp($forecastTotal) }}</div></div>
</section>

<section class="grid two section">
    <div class="card">
        <h2>Dynamic Chart of Accounts (CoA)</h2>
        <form method="post" action="{{ route('finance-suite.coa.store') }}" class="form-grid">
            @csrf
            <div><label>Kode</label><input name="code" placeholder="1101" required></div>
            <div><label>Nama Akun</label><input name="name" placeholder="Kas Bank" required></div>
            <div><label>Tipe</label><select name="type"><option>asset</option><option>liability</option><option>equity</option><option>revenue</option><option>expense</option></select></div>
            <div><label>Parent</label><select name="parent_id"><option value="">Root account</option>@foreach($coaOptions as $account)<option value="{{ $account->id }}">{{ $account->code }} - {{ $account->name }}</option>@endforeach</select></div>
            <div class="full"><button>Tambah CoA</button></div>
        </form>
        <div class="wide section">
            <table><thead><tr><th>Code</th><th>Name</th><th>Type</th><th>Status</th></tr></thead><tbody>
                @foreach($accounts as $account)
                    <tr><td class="font-bold">{{ $account->code }}</td><td>{{ $account->name }}<br><span class="muted">{{ $account->parent?->code }}</span></td><td><span class="badge">{{ $account->type }}</span></td><td><span class="badge badge-{{ $account->is_active ? 'active' : 'void' }}">{{ $account->is_active ? 'active' : 'inactive' }}</span></td></tr>
                @endforeach
            </tbody></table>
            <div class="pager"><span>{{ $accounts->count() }} dari {{ $accounts->total() }} akun</span>{{ $accounts->links() }}</div>
        </div>
    </div>

    <div class="card">
        <h2>General Ledger & Journaling</h2>
        <form method="post" action="{{ route('finance-suite.journals.store') }}" class="grid">
            @csrf
            <div class="form-grid">
                <div><label>Tanggal</label><input name="entry_date" type="date" value="{{ now()->toDateString() }}" required></div>
                <div><label>Reference</label><input name="reference" placeholder="Manual / adjustment"></div>
                <div class="full"><label>Memo</label><input name="memo" placeholder="Keterangan jurnal"></div>
            </div>
            @for($i = 0; $i < 2; $i++)
                <div class="grid grid-cols-1 gap-3 rounded-2xl border border-slate-200 p-3 md:grid-cols-4">
                    <select name="lines[{{ $i }}][chart_account_id]" required><option value="">Pilih akun</option>@foreach($coaOptions as $account)<option value="{{ $account->id }}">{{ $account->code }} - {{ $account->name }}</option>@endforeach</select>
                    <input name="lines[{{ $i }}][debit]" type="number" min="0" value="{{ $i === 0 ? '' : 0 }}" placeholder="Debit">
                    <input name="lines[{{ $i }}][credit]" type="number" min="0" value="{{ $i === 1 ? '' : 0 }}" placeholder="Credit">
                    <input name="lines[{{ $i }}][description]" placeholder="Deskripsi">
                </div>
            @endfor
            <button>Buat Journal</button>
        </form>
        <div class="wide section">
            <table><thead><tr><th>No</th><th>Tanggal</th><th>Memo</th><th>Lines</th></tr></thead><tbody>
                @foreach($journals as $journal)
                    <tr><td class="font-bold">{{ $journal->number }}</td><td>{{ $journal->entry_date }}</td><td>{{ $journal->memo }}</td><td>{{ $journal->lines->count() }} line</td></tr>
                @endforeach
            </tbody></table>
            <div class="pager">{{ $journals->links() }}</div>
        </div>
    </div>
</section>

<section class="grid two section">
    <div class="card">
        <h2>Billing & Accounts Receivable (AR)</h2>
        <p class="muted">AR memakai data invoice existing, termasuk paid/partial/outstanding.</p>
        <div class="wide section">
            <table><thead><tr><th>Invoice</th><th>Status</th><th>Due</th><th>Outstanding</th></tr></thead><tbody>
                @foreach($invoices as $invoice)
                    <tr><td class="font-bold">{{ $invoice->number }}<br><span class="muted">{{ $invoice->project?->code }}</span></td><td><span class="badge badge-{{ $invoice->status }}">{{ $invoice->status }}</span></td><td>{{ $invoice->due_date }}</td><td>{{ $rp($invoice->amount - $invoice->paid_amount) }}</td></tr>
                @endforeach
            </tbody></table>
            <div class="pager">{{ $invoices->links() }}</div>
        </div>
    </div>

    <div class="card">
        <h2>Recurring & Subscription Billing</h2>
        <form method="post" action="{{ route('finance-suite.recurring.store') }}" class="form-grid">
            @csrf
            <div><label>Client</label><select name="client_id"><option value="">Manual</option>@foreach($clients as $client)<option value="{{ $client->id }}">{{ $client->name }}</option>@endforeach</select></div>
            <div><label>Nama Subscription</label><input name="name" required></div>
            <div><label>Frequency</label><select name="frequency"><option>monthly</option><option>weekly</option><option>quarterly</option><option>yearly</option></select></div>
            <div><label>Amount</label><input name="amount" type="number" min="0" required></div>
            <div><label>Tax %</label><input name="tax_rate" type="number" min="0" value="0"></div>
            <div><label>Next Invoice</label><input name="next_invoice_date" type="date" value="{{ now()->toDateString() }}" required></div>
            <div><label>Status</label><select name="status"><option>active</option><option>paused</option><option>ended</option></select></div>
            <div><label>End Date</label><input name="end_date" type="date"></div>
            <div class="full"><button>Tambah Recurring</button></div>
        </form>
        <div class="wide section">
            <table><thead><tr><th>Name</th><th>Client</th><th>Next</th><th>Amount</th></tr></thead><tbody>
                @foreach($recurrings as $row)
                    <tr><td class="font-bold">{{ $row->name }}</td><td>{{ $row->client?->name ?? '-' }}</td><td>{{ $row->next_invoice_date }}</td><td>{{ $rp($row->amount) }}</td></tr>
                @endforeach
            </tbody></table>
            <div class="pager">{{ $recurrings->links() }}</div>
        </div>
    </div>
</section>

<section class="grid two section">
    <div class="card">
        <h2>Automated Payment Reminder</h2>
        <form method="post" action="{{ route('finance-suite.reminders.store') }}" class="form-grid">
            @csrf
            <div class="full"><label>Invoice</label><select name="invoice_id" required>@foreach($unpaidInvoices as $invoice)<option value="{{ $invoice->id }}">{{ $invoice->number }} - due {{ $invoice->due_date }}</option>@endforeach</select></div>
            <div><label>Tanggal Reminder</label><input name="reminder_date" type="date" value="{{ now()->toDateString() }}" required></div>
            <div><label>Channel</label><select name="channel"><option>email</option><option>whatsapp</option><option>phone</option></select></div>
            <div><label>Status</label><select name="status"><option>scheduled</option><option>sent</option><option>cancelled</option></select></div>
            <div class="full"><label>Message</label><textarea name="message" placeholder="Template reminder pembayaran"></textarea></div>
            <div class="full"><button>Jadwalkan Reminder</button></div>
        </form>
        <div class="wide section">
            <table><thead><tr><th>Invoice</th><th>Date</th><th>Channel</th><th>Status</th></tr></thead><tbody>
                @foreach($reminders as $row)
                    <tr><td>{{ $row->invoice?->number }}</td><td>{{ $row->reminder_date }}</td><td>{{ $row->channel }}</td><td><span class="badge badge-{{ $row->status === 'scheduled' ? 'pending' : 'active' }}">{{ $row->status }}</span></td></tr>
                @endforeach
            </tbody></table>
            <div class="pager">{{ $reminders->links() }}</div>
        </div>
    </div>

    <div class="card">
        <h2>Accounts Payable (AP) & Vendor Bills</h2>
        <form method="post" action="{{ route('finance-suite.vendor-bills.store') }}" class="form-grid">
            @csrf
            <div><label>Vendor</label><input name="vendor_name" required></div>
            <div><label>Bill Number</label><input name="bill_number" placeholder="Auto jika kosong"></div>
            <div><label>Project</label><select name="project_id"><option value="">Company</option>@foreach($projects as $project)<option value="{{ $project->id }}">{{ $project->code }}</option>@endforeach</select></div>
            <div><label>Bank/Kas</label><select name="bank_account_id"><option value="">-</option>@foreach($bankAccounts as $bank)<option value="{{ $bank->id }}">{{ $bank->name }}</option>@endforeach</select></div>
            <div><label>Bill Date</label><input name="bill_date" type="date" value="{{ now()->toDateString() }}" required></div>
            <div><label>Due Date</label><input name="due_date" type="date"></div>
            <div><label>Amount</label><input name="amount" type="number" min="0" required></div>
            <div><label>Tax %</label><input name="tax_rate" type="number" min="0" value="0"></div>
            <div><label>Status</label><select name="status"><option>unpaid</option><option>partial</option><option>paid</option><option>void</option></select></div>
            <div class="full"><label>Notes</label><input name="notes"></div>
            <div class="full"><button>Tambah Vendor Bill</button></div>
        </form>
    </div>
</section>

<section class="grid two section">
    <div class="card">
        <h2>Vendor Bill Payment Scheduling</h2>
        <form method="post" action="{{ route('finance-suite.vendor-payments.store') }}" class="form-grid">
            @csrf
            <div class="full"><label>Vendor Bill</label><select name="vendor_bill_id" required>@foreach($unpaidVendorBills as $bill)<option value="{{ $bill->id }}">{{ $bill->bill_number }} - {{ $bill->vendor_name }} - sisa {{ $rp($bill->amount - $bill->paid_amount) }}</option>@endforeach</select></div>
            <div><label>Bank/Kas</label><select name="bank_account_id"><option value="">Default</option>@foreach($bankAccounts as $bank)<option value="{{ $bank->id }}">{{ $bank->name }}</option>@endforeach</select></div>
            <div><label>Amount</label><input name="amount" type="number" min="1" required></div>
            <div><label>Payment Date</label><input name="payment_date" type="date" value="{{ now()->toDateString() }}" required></div>
            <div><label>Reference</label><input name="reference"></div>
            <div class="full"><button>Bayar Vendor Bill</button></div>
        </form>
        <div class="wide section">
            <table><thead><tr><th>Bill</th><th>Vendor</th><th>Status</th><th>Outstanding</th></tr></thead><tbody>
                @foreach($vendorBills as $bill)
                    <tr><td class="font-bold">{{ $bill->bill_number }}</td><td>{{ $bill->vendor_name }}<br><span class="muted">{{ $bill->project?->code }}</span></td><td><span class="badge badge-{{ $bill->status === 'unpaid' ? 'pending' : $bill->status }}">{{ $bill->status }}</span></td><td>{{ $rp($bill->amount - $bill->paid_amount) }}</td></tr>
                @endforeach
            </tbody></table>
            <div class="pager">{{ $vendorBills->links() }}</div>
        </div>
    </div>

    <div class="card">
        <h2>Cash & Bank Management</h2>
        <p class="muted">Cash/bank memakai master bank account dan transaksi cashflow existing.</p>
        <div class="wide section">
            <table><thead><tr><th>Bank/Kas</th><th>Opening</th><th>Bank</th><th>Account</th></tr></thead><tbody>
                @foreach($bankAccounts as $bank)
                    <tr><td class="font-bold">{{ $bank->name }}</td><td>{{ $rp($bank->opening_balance) }}</td><td>{{ $bank->bank_name }}</td><td>{{ $bank->account_number }}</td></tr>
                @endforeach
            </tbody></table>
        </div>
        <div class="section grid two">
            <div class="notice">Total Income: <strong>{{ $rp($cashSummary['income']) }}</strong></div>
            <div class="notice">Total Expense: <strong>{{ $rp($cashSummary['expense']) }}</strong></div>
        </div>
    </div>
</section>

<section class="grid two section">
    <div class="card">
        <h2>Budget & Forecast</h2>
        <form method="post" action="{{ route('finance-suite.budgets.store') }}" class="form-grid">
            @csrf
            <div><label>Period</label><input name="period" value="{{ now()->format('Y-m') }}" required></div>
            <div><label>Project</label><select name="project_id"><option value="">Company</option>@foreach($projects as $project)<option value="{{ $project->id }}">{{ $project->code }}</option>@endforeach</select></div>
            <div class="full"><label>CoA</label><select name="chart_account_id"><option value="">No account</option>@foreach($coaOptions as $account)<option value="{{ $account->id }}">{{ $account->code }} - {{ $account->name }}</option>@endforeach</select></div>
            <div><label>Budget</label><input name="budget_amount" type="number" min="0" required></div>
            <div><label>Forecast</label><input name="forecast_amount" type="number" min="0" required></div>
            <div class="full"><label>Notes</label><input name="notes"></div>
            <div class="full"><button>Simpan Budget</button></div>
        </form>
        <div class="wide section">
            <table><thead><tr><th>Period</th><th>Project/Account</th><th>Budget</th><th>Forecast</th></tr></thead><tbody>
                @foreach($budgets as $budget)
                    <tr><td>{{ $budget->period }}</td><td>{{ $budget->project?->code ?? 'Company' }}<br><span class="muted">{{ $budget->account?->code }} {{ $budget->account?->name }}</span></td><td>{{ $rp($budget->budget_amount) }}</td><td>{{ $rp($budget->forecast_amount) }}</td></tr>
                @endforeach
            </tbody></table>
            <div class="pager">{{ $budgets->links() }}</div>
        </div>
    </div>

    <div class="card">
        <h2>Tax Calculation & Management</h2>
        <form method="post" action="{{ route('finance-suite.taxes.store') }}" class="form-grid">
            @csrf
            <div><label>Nama Rule</label><input name="name" placeholder="PPN 11%" required></div>
            <div><label>Tax Type</label><select name="tax_type"><option>PPN</option><option>PPh 21</option><option>PPh 23</option><option>PPh 4(2)</option></select></div>
            <div><label>Rate %</label><input name="rate" type="number" min="0" step="0.01" required></div>
            <div><label>Direction</label><select name="direction"><option>output</option><option>input</option><option>withholding</option></select></div>
            <div class="full"><button>Tambah Tax Rule</button></div>
        </form>
        <div class="wide section">
            <table><thead><tr><th>Rule</th><th>Type</th><th>Rate</th><th>Direction</th></tr></thead><tbody>
                @foreach($taxRules as $tax)
                    <tr><td class="font-bold">{{ $tax->name }}</td><td><span class="badge">{{ $tax->tax_type }}</span></td><td>{{ $tax->rate }}%</td><td>{{ $tax->direction }}</td></tr>
                @endforeach
            </tbody></table>
            <div class="pager">{{ $taxRules->links() }}</div>
        </div>
    </div>
</section>
@endsection
