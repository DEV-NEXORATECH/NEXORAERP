@extends('layouts.erp', ['activePage' => 'reports-reconciliation', 'pageTitle' => 'Bank Reconciliation Report'])

@section('content')
<section class="report-hero">
    <div>
        <span>Financial Intelligence</span>
        <h1>Bank Reconciliation</h1>
        <p>Rekonsiliasi bank per akun dengan book balance.</p>
    </div>
</section>

<section class="filter-panel report-filter section">
    <div class="filter-panel-header">
        <div class="filter-panel-icon">{!! $icon('cashflow') !!}</div>
        <div>
            <h3>Filter Report</h3>
            <p class="muted">Filter berdasarkan periode dan bank/kas.</p>
        </div>
    </div>
    <form method="get" action="{{ route('reports.reconciliation') }}" class="filter-grid">
        <div class="filter-field"><label>Dari</label><input name="date_from" type="date" value="{{ request('date_from') }}"></div>
        <div class="filter-field"><label>Sampai</label><input name="date_to" type="date" value="{{ request('date_to') }}"></div>
        <div class="filter-field"><label>Bank/Kas</label><select name="bank_account_id"><option value="">Semua</option>@foreach($bankAccounts as $bank)<option value="{{ $bank->id }}" @selected((int) request('bank_account_id') === $bank->id)>{{ $bank->name }}</option>@endforeach</select></div>
        <div class="filter-actions xl:col-span-6">
            <button>Apply Filter</button>
            <a class="button ghost" href="{{ route('reports.reconciliation') }}">Reset</a>
        </div>
    </form>
</section>

<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5 section">
    <h2 class="font-bold text-lg mb-4">Reconciliation</h2>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-[#d7e3ef]">
                    <th class="text-left py-2">Bank/Kas</th>
                    <th class="text-right py-2">Opening</th>
                    <th class="text-right py-2">Cash In</th>
                    <th class="text-right py-2">Cash Out</th>
                    <th class="text-right py-2">Book Balance</th>
                    <th class="text-right py-2">Unreconciled</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bankReconciliation as $row)
                    <tr class="border-b border-[#d7e3ef]/50">
                        <td class="py-2 font-bold">{{ $row['bank']->name }}<br><span class="muted text-xs">{{ $row['bank']->bank_name }} {{ $row['bank']->account_number }}</span></td>
                        <td class="text-right py-2">{{ $rp($row['bank']->opening_balance) }}</td>
                        <td class="text-right py-2">{{ $rp($row['income']) }}</td>
                        <td class="text-right py-2">{{ $rp($row['expense']) }}</td>
                        <td class="text-right py-2 font-black">{{ $rp($row['book_balance']) }}</td>
                        <td class="text-right py-2">{{ $rp($row['unreconciled']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endsection
