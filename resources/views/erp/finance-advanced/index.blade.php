@extends('layouts.erp', ['activePage' => 'finance-advanced', 'pageTitle' => 'Finance Advanced'])

@section('content')
<section class="grid cards section">
    <div class="stat-card"><div class="stat-card-label">Fixed Assets</div><div class="stat-card-metric">{{ $assets->total() }}</div><div class="muted">Depreciation base</div></div>
    <div class="stat-card"><div class="stat-card-label">Currency Rates</div><div class="stat-card-metric">{{ $rates->total() }}</div><div class="muted">Multi-currency</div></div>
    <div class="stat-card"><div class="stat-card-label">Revenue Schedule</div><div class="stat-card-metric">{{ $schedules->total() }}</div><div class="muted">Deferred revenue</div></div>
    <div class="stat-card"><div class="stat-card-label">3-Way Match</div><div class="stat-card-metric">{{ $matches->total() }}</div><div class="muted">PO + receipt + bill</div></div>
</section>

<section class="grid two section">
    <div class="card">
        <h2>Fixed Assets & Depreciation</h2>
        <form method="post" action="{{ route('finance-advanced.assets.store') }}" class="form-grid">
            @csrf
            <div><label>Asset Name</label><input name="name" required></div>
            <div><label>Category</label><input name="category"></div>
            <div><label>Acquisition Date</label><input name="acquisition_date" type="date"></div>
            <div><label>Cost</label><input name="acquisition_cost" type="number" min="0" required></div>
            <div><label>Useful Life (month)</label><input name="useful_life_months" type="number" min="1" value="36" required></div>
            <div><label>Accum. Depreciation</label><input name="accumulated_depreciation" type="number" min="0" value="0"></div>
            <div><label>Status</label><select name="status"><option>active</option><option>maintenance</option><option>disposed</option></select></div>
            <div class="full"><button>Simpan Asset</button></div>
        </form>
        <table class="section"><thead><tr><th>Asset</th><th>Book Value</th><th>Status</th></tr></thead><tbody>@foreach($assets as $row)<tr><td class="font-bold">{{ $row->name }}<br><span class="muted">{{ $row->category }}</span></td><td>{{ $rp($row->acquisition_cost - $row->accumulated_depreciation) }}</td><td><span class="badge badge-{{ $row->status === 'active' ? 'active' : 'pending' }}">{{ $row->status }}</span></td></tr>@endforeach</tbody></table>
        <div class="pager">{{ $assets->links() }}</div>
    </div>

    <div class="card">
        <h2>Multi-Currency & Variance</h2>
        <form method="post" action="{{ route('finance-advanced.rates.store') }}" class="form-grid">
            @csrf
            <div><label>Currency</label><input name="currency" maxlength="3" value="USD" required></div>
            <div><label>Rate Date</label><input name="rate_date" type="date" value="{{ now()->toDateString() }}" required></div>
            <div><label>Rate to IDR</label><input name="rate_to_idr" type="number" step="0.0001" min="0" required></div>
            <div class="full"><button>Simpan Rate</button></div>
        </form>
        <form method="post" action="{{ route('finance-advanced.variances.store') }}" class="form-grid section">
            @csrf
            <div><label>Invoice</label><select name="invoice_id"><option value="">No invoice</option>@foreach($invoices as $invoice)<option value="{{ $invoice->id }}">{{ $invoice->number }}</option>@endforeach</select></div>
            <div><label>Currency</label><input name="currency" maxlength="3" value="USD" required></div>
            <div><label>Foreign Amount</label><input name="foreign_amount" type="number" min="0" required></div>
            <div><label>Invoice Rate</label><input name="invoice_rate" type="number" step="0.0001" min="0" required></div>
            <div><label>Payment Rate</label><input name="payment_rate" type="number" step="0.0001" min="0" required></div>
            <div><label>Status</label><select name="status"><option>draft</option><option>posted</option></select></div>
            <div class="full"><button>Hitung Variance</button></div>
        </form>
        <table class="section"><thead><tr><th>Currency</th><th>Rate</th></tr></thead><tbody>@foreach($rates as $row)<tr><td>{{ $row->currency }}<br><span class="muted">{{ $row->rate_date }}</span></td><td>{{ number_format($row->rate_to_idr, 4) }}</td></tr>@endforeach</tbody></table>
        <div class="pager">{{ $rates->links() }}</div>
    </div>
</section>

<section class="grid two section">
    <div class="card">
        <h2>Deferred Revenue Recognition</h2>
        <form method="post" action="{{ route('finance-advanced.revenues.store') }}" class="form-grid">
            @csrf
            <div class="full"><label>Invoice</label><select name="invoice_id"><option value="">Manual schedule</option>@foreach($invoices as $invoice)<option value="{{ $invoice->id }}">{{ $invoice->number }}</option>@endforeach</select></div>
            <div><label>Description</label><input name="description" required></div>
            <div><label>Month</label><input name="recognition_month" value="{{ now()->format('Y-m') }}" required></div>
            <div><label>Amount</label><input name="amount" type="number" min="0" required></div>
            <div><label>Status</label><select name="status"><option>scheduled</option><option>recognized</option><option>deferred</option></select></div>
            <div class="full"><button>Simpan Schedule</button></div>
        </form>
        <table class="section"><thead><tr><th>Revenue</th><th>Month</th><th>Status</th></tr></thead><tbody>@foreach($schedules as $row)<tr><td>{{ $row->description }}<br><span class="muted">{{ $rp($row->amount) }}</span></td><td>{{ $row->recognition_month }}</td><td><span class="badge badge-{{ $row->status === 'recognized' ? 'active' : 'pending' }}">{{ $row->status }}</span></td></tr>@endforeach</tbody></table>
        <div class="pager">{{ $schedules->links() }}</div>
    </div>

    <div class="card">
        <h2>Bank Reconciliation</h2>
        <form method="post" action="{{ route('finance-advanced.reconciliations.store') }}" class="form-grid">
            @csrf
            <div><label>Bank</label><select name="bank_account_id"><option value="">No bank</option>@foreach($banks as $bank)<option value="{{ $bank->id }}">{{ $bank->name }}</option>@endforeach</select></div>
            <div><label>Cashflow</label><select name="cashflow_id"><option value="">Unlinked</option>@foreach($cashflows as $cashflow)<option value="{{ $cashflow->id }}">{{ $cashflow->description }} - {{ $rp($cashflow->amount) }}</option>@endforeach</select></div>
            <div><label>Statement Date</label><input name="statement_date" type="date" value="{{ now()->toDateString() }}" required></div>
            <div><label>Reference</label><input name="statement_reference"></div>
            <div><label>Statement Amount</label><input name="statement_amount" type="number" required></div>
            <div><label>Match Status</label><select name="match_status"><option>unmatched</option><option>matched</option><option>variance</option></select></div>
            <div class="full"><button>Simpan Recon</button></div>
        </form>
        <table class="section"><thead><tr><th>Statement</th><th>Amount</th><th>Status</th></tr></thead><tbody>@foreach($reconciliations as $row)<tr><td>{{ $row->statement_date }}<br><span class="muted">{{ $row->statement_reference }}</span></td><td>{{ $rp($row->statement_amount) }}</td><td><span class="badge badge-{{ $row->match_status === 'matched' ? 'active' : 'pending' }}">{{ $row->match_status }}</span></td></tr>@endforeach</tbody></table>
        <div class="pager">{{ $reconciliations->links() }}</div>
    </div>
</section>

<section class="card section">
    <h2>Three-Way Matching: PO + Receipt + Vendor Bill</h2>
    <form method="post" action="{{ route('finance-advanced.matches.store') }}" class="form-grid">
        @csrf
        <div><label>PO</label><select name="purchase_order_id"><option value="">No PO</option>@foreach($purchaseOrders as $po)<option value="{{ $po->id }}">{{ $po->number }}</option>@endforeach</select></div>
        <div><label>Receipt</label><select name="goods_receipt_id"><option value="">No receipt</option>@foreach($receipts as $receipt)<option value="{{ $receipt->id }}">Receipt #{{ $receipt->id }}</option>@endforeach</select></div>
        <div><label>Vendor Bill</label><select name="vendor_bill_id"><option value="">No bill</option>@foreach($vendorBills as $bill)<option value="{{ $bill->id }}">{{ $bill->bill_number }} - {{ $bill->vendor_name }}</option>@endforeach</select></div>
        <div><label>Variance</label><input name="variance_amount" type="number" value="0"></div>
        <div><label>Status</label><select name="status"><option>matched</option><option>variance</option><option>blocked</option></select></div>
        <div class="full"><label>Notes</label><input name="notes"></div>
        <div class="full"><button>Simpan Matching</button></div>
    </form>
    <div class="wide section">
        <table><thead><tr><th>PO</th><th>Receipt</th><th>Vendor Bill</th><th>Variance</th><th>Status</th></tr></thead><tbody>
            @foreach($matches as $row)
                <tr><td>#{{ $row->purchase_order_id ?? '-' }}</td><td>#{{ $row->goods_receipt_id ?? '-' }}</td><td>#{{ $row->vendor_bill_id ?? '-' }}</td><td>{{ $rp($row->variance_amount) }}</td><td><span class="badge badge-{{ $row->status === 'matched' ? 'active' : 'pending' }}">{{ $row->status }}</span></td></tr>
            @endforeach
        </tbody></table>
        <div class="pager">{{ $matches->links() }}</div>
    </div>
</section>
@endsection
