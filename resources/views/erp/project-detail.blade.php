@extends('layouts.app', ['title' => $project->code.' Detail'])

@php $rp = fn ($value) => 'Rp '.number_format((float) $value, 0, ',', '.'); @endphp

@section('body')
<main class="shell">
    <header class="topbar">
        <div>
            <h1>{{ $project->code }} - {{ $project->name }}</h1>
            <div class="muted">{{ $project->client }} | {{ $project->status }}</div>
        </div>
        <a class="button ghost" href="{{ route('dashboard') }}">Kembali</a>
    </header>

    <section class="grid cards">
        <div class="card"><div class="muted">Income</div><div class="metric good">{{ $rp($summary['income']) }}</div></div>
        <div class="card"><div class="muted">Expense</div><div class="metric bad">{{ $rp($summary['expense']) }}</div></div>
        <div class="card"><div class="muted">Profit/Loss</div><div class="metric">{{ $rp($summary['balance']) }}</div></div>
        <div class="card"><div class="muted">Margin</div><div class="metric">{{ number_format($profitMargin, 2) }}%</div></div>
    </section>

    <section class="grid two section">
        <div class="card wide">
            <h2>Costing Detail</h2>
            <table>
                <thead><tr><th>Tanggal</th><th>Type</th><th>Cost Type</th><th>Vendor</th><th>Amount</th></tr></thead>
                <tbody>
                    @foreach($project->cashflows as $flow)
                        <tr><td>{{ $flow->transaction_date }}</td><td>{{ $flow->type }}</td><td>{{ $flow->cost_type }}</td><td>{{ $flow->vendor }}</td><td>{{ $rp($flow->amount) }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card wide">
            <h2>Invoice</h2>
            <table>
                @foreach($project->invoices as $invoice)
                    <tr><td>{{ $invoice->number }}<br><span class="muted">{{ $invoice->status }}</span></td><td>{{ $rp($invoice->paid_amount) }} / {{ $rp($invoice->amount) }}</td></tr>
                @endforeach
            </table>
            <h2 style="margin-top:16px">Payroll & Reimbursement</h2>
            <table>
                @foreach($project->salaries as $salary)
                    <tr><td>Salary {{ $salary->employee->name }} {{ $salary->period }}</td><td>{{ $rp($salary->net_salary) }}</td></tr>
                @endforeach
                @foreach($project->reimbursements as $reimbursement)
                    <tr><td>Reimburse {{ $reimbursement->employee->name }} {{ $reimbursement->category }}</td><td>{{ $rp($reimbursement->amount) }}</td></tr>
                @endforeach
            </table>
        </div>
    </section>
    <section class="card section wide">
        <h2>Activity Timeline</h2>
        <table>
            <thead><tr><th>Waktu</th><th>Aktivitas</th><th>Detail</th></tr></thead>
            <tbody>
            @foreach($timeline as $item)
                <tr><td>{{ $item['date'] }}</td><td>{{ $item['label'] }}</td><td>{{ $item['desc'] }}</td></tr>
            @endforeach
            </tbody>
        </table>
    </section>
</main>
@endsection
