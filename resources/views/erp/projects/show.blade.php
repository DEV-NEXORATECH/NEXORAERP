@extends('layouts.erp', ['activePage' => 'projects', 'pageTitle' => $project->code.' Detail'])

@section('content')
<div class="section-head">
    <div>
        <h1>{{ $project->code }} — {{ $project->name }}</h1>
        <div class="muted">{{ $project->client }} &bull; <span class="badge">{{ $project->status }}</span></div>
    </div>
    <a class="button ghost" href="{{ route('projects.index-page') }}">Kembali</a>
</div>

<div class="grid cards" style="margin-bottom:24px">
    <div class="card">
        <div class="muted">Income</div>
        <div class="metric good">{{ $rp($summary['income']) }}</div>
    </div>
    <div class="card">
        <div class="muted">Expense</div>
        <div class="metric bad">{{ $rp($summary['expense']) }}</div>
    </div>
    <div class="card">
        <div class="muted">Profit / Loss</div>
        <div class="metric {{ $summary['balance'] >= 0 ? 'good' : 'bad' }}">{{ $rp($summary['balance']) }}</div>
    </div>
    <div class="card">
        <div class="muted">Margin</div>
        <div class="metric">{{ number_format($profitMargin, 2) }}%</div>
    </div>
</div>

<div class="grid two section">
    <section class="card wide">
        <div class="section-head"><h2>Costing Detail</h2></div>
        <table>
            <thead>
                <tr><th>Tanggal</th><th>Type</th><th>Cost Type</th><th>Vendor</th><th>Amount</th></tr>
            </thead>
            <tbody>
                @forelse($project->cashflows as $flow)
                    <tr>
                        <td class="text-xs text-slate-500">{{ $flow->transaction_date }}</td>
                        <td><span class="badge {{ $flow->type === 'income' ? 'good' : '' }}">{{ $flow->type }}</span></td>
                        <td class="muted">{{ $flow->cost_type }}</td>
                        <td>{{ $flow->vendor }}</td>
                        <td class="font-bold">{{ $rp($flow->amount) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-8 text-center text-slate-500">Belum ada cashflow.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <div>
        <section class="card wide" style="margin-bottom:16px">
            <div class="section-head"><h2>Invoice</h2></div>
            <table>
                <thead><tr><th>Nomor</th><th>Status</th><th>Paid / Total</th></tr></thead>
                <tbody>
                    @forelse($project->invoices as $invoice)
                        <tr>
                            <td class="font-bold">{{ $invoice->number }}</td>
                            <td><span class="badge">{{ $invoice->status }}</span></td>
                            <td>{{ $rp($invoice->paid_amount) }} / {{ $rp($invoice->amount) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="py-4 text-center text-slate-500">Belum ada invoice.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        <section class="card wide">
            <div class="section-head"><h2>Payroll & Reimbursement</h2></div>
            <table>
                <tbody>
                    @foreach($project->salaries as $salary)
                        <tr>
                            <td>Salary — {{ $salary->employee->name }}</td>
                            <td class="muted">{{ $salary->period }}</td>
                            <td class="font-bold">{{ $rp($salary->net_salary) }}</td>
                        </tr>
                    @endforeach
                    @foreach($project->reimbursements as $reimbursement)
                        <tr>
                            <td>Reimburse — {{ $reimbursement->employee->name }}</td>
                            <td class="muted">{{ $reimbursement->category }}</td>
                            <td class="font-bold">{{ $rp($reimbursement->amount) }}</td>
                        </tr>
                    @endforeach
                    @if($project->salaries->isEmpty() && $project->reimbursements->isEmpty())
                        <tr><td colspan="3" class="py-4 text-center text-slate-500">Belum ada data.</td></tr>
                    @endif
                </tbody>
            </table>
        </section>
    </div>
</div>

<section class="card section wide">
    <div class="section-head"><h2>Activity Timeline</h2></div>
    <table>
        <thead><tr><th>Waktu</th><th>Aktivitas</th><th>Detail</th></tr></thead>
        <tbody>
            @forelse($timeline as $item)
                <tr>
                    <td class="text-xs text-slate-500">{{ $item['date'] }}</td>
                    <td class="font-bold">{{ $item['label'] }}</td>
                    <td class="muted">{{ $item['desc'] }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="py-8 text-center text-slate-500">Belum ada aktivitas.</td></tr>
            @endforelse
        </tbody>
    </table>
</section>
@endsection
