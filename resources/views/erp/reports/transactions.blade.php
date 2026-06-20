@extends('layouts.erp', ['activePage' => 'reports-transactions', 'pageTitle' => 'Transaction Listings'])

@section('content')
<section class="report-hero">
    <div>
        <span>Financial Intelligence</span>
        <h1>Transaction Listings</h1>
        <p>Daftar transaksi cashflow dengan filtering dan pagination.</p>
    </div>
</section>

<section class="filter-panel report-filter section">
    <div class="filter-panel-header">
        <div class="filter-panel-icon">{!! $icon('cashflow') !!}</div>
        <div>
            <h3>Filter Transaction</h3>
            <p class="muted">Filter berdasarkan periode, project, dan bank/kas.</p>
        </div>
    </div>
    <form method="get" action="{{ route('reports.transactions') }}" class="filter-grid">
        <div class="filter-field"><label>Dari</label><input name="date_from" type="date" value="{{ request('date_from') }}"></div>
        <div class="filter-field"><label>Sampai</label><input name="date_to" type="date" value="{{ request('date_to') }}"></div>
        <div class="filter-field"><label>Project</label><select name="project_id"><option value="">Semua</option>@foreach($projects as $project)<option value="{{ $project->id }}" @selected((int) request('project_id') === $project->id)>{{ $project->code }} - {{ $project->name }}</option>@endforeach</select></div>
        <div class="filter-field"><label>Bank/Kas</label><select name="bank_account_id"><option value="">Semua</option>@foreach($bankAccounts as $bank)<option value="{{ $bank->id }}" @selected((int) request('bank_account_id') === $bank->id)>{{ $bank->name }}</option>@endforeach</select></div>
        <div class="filter-actions xl:col-span-6">
            <button>Apply Filter</button>
            <a class="button ghost" href="{{ route('reports.transactions') }}">Reset</a>
        </div>
    </form>
</section>

<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5 section">
    <h2 class="font-bold text-lg mb-4">Transactions</h2>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-[#d7e3ef]">
                    <th class="text-left py-2">Tanggal</th>
                    <th class="text-left py-2">Project</th>
                    <th class="text-left py-2">Type</th>
                    <th class="text-left py-2">Category</th>
                    <th class="text-left py-2">Bank</th>
                    <th class="text-right py-2">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $flow)
                    <tr class="border-b border-[#d7e3ef]/50">
                        <td class="py-2">{{ $flow->transaction_date }}</td>
                        <td class="py-2">{{ $flow->project?->code ?? 'Company' }}</td>
                        <td class="py-2"><span class="badge badge-{{ $flow->type }}">{{ $flow->type }}</span></td>
                        <td class="py-2">{{ $flow->category }}</td>
                        <td class="py-2">{{ $flow->bankAccount?->name ?? '-' }}</td>
                        <td class="text-right py-2">{{ $rp($flow->amount) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="pager mt-4">{{ $transactions->links() }}</div>
</section>
@endsection
