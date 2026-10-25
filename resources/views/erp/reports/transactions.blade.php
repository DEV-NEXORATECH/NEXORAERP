@extends('layouts.erp', ['activePage' => 'reports-transactions', 'pageTitle' => 'Transaction Listings'])

@section('content')
<div class="space-y-4">
    {{-- Card 1 — Page Header & Global Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Transaction Listings</h1>
            <p class="text-xs text-gray-500">Daftar transaksi cashflow dengan filtering dan pagination.</p>
        </div>
        <x-erp.toolbar-actions module="report-transactions" :can-create="false" />
    </div>

    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-card">
        <form method="get" action="{{ route('reports.transactions') }}" class="filter-toolbar">
            <input name="date_from" type="date" value="{{ request('date_from') }}" class="filter-date">
            <input name="date_to" type="date" value="{{ request('date_to') }}" class="filter-date">
            <select name="project_id" class="filter-select">
                <option value="">Semua Project</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" @selected((int) request('project_id') === $project->id)>{{ $project->code }} - {{ $project->name }}</option>
                @endforeach
            </select>
            <select name="bank_account_id" class="filter-select">
                <option value="">Semua Bank/Kas</option>
                @foreach($bankAccounts as $bank)
                    <option value="{{ $bank->id }}" @selected((int) request('bank_account_id') === $bank->id)>{{ $bank->name }}</option>
                @endforeach
            </select>
            <div class="flex items-center gap-2">
                <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition">Filter</button>
                <a href="{{ route('reports.transactions') }}" class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Reset</a>
            </div>
        </form>
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Tanggal</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Project</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Type</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Category</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Bank</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($transactions as $flow)
                        <tr>
                            <td class="px-3 py-2 text-xs text-gray-700 whitespace-nowrap">{{ $flow->transaction_date }}</td>
                            <td class="px-3 py-2 text-xs text-gray-700">{{ $flow->project?->code ?? 'Company' }}</td>
                            <td class="px-3 py-2 text-xs text-gray-700">
                                <span class="rounded-full px-2 py-0.5 text-xs font-bold {{ $flow->type === 'in' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                    {{ $flow->type }}
                                </span>
                            </td>
                            <td class="px-3 py-2 text-xs text-gray-700">{{ $flow->category }}</td>
                            <td class="px-3 py-2 text-xs text-gray-700">{{ $flow->bankAccount?->name ?? '-' }}</td>
                            <td class="px-3 py-2 text-xs text-right font-bold text-gray-900">{{ $rp($flow->amount) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500">Tidak ada transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Card 4 — Standalone Pagination --}}
    <x-erp.pagination :paginator="$transactions" />
</div>
@endsection