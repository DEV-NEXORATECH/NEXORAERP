@extends('layouts.erp', ['activePage' => 'cashflows', 'pageTitle' => 'Cashflow'])

@section('content')

{{-- Summary chips --}}
<div class="filter-chips section">
    <div class="filter-chip active">
        <div class="filter-chip-icon">{!! $icon('cashflow') !!}</div>
        <div class="filter-chip-count">{{ $rp($summary['income']) }}</div>
        <div class="filter-chip-label">Income</div>
        <div class="filter-chip-sub">total pemasukan</div>
    </div>
    <div class="filter-chip">
        <div class="filter-chip-icon">{!! $icon('cashflow') !!}</div>
        <div class="filter-chip-count">{{ $rp($summary['expense']) }}</div>
        <div class="filter-chip-label">Expense</div>
        <div class="filter-chip-sub">total pengeluaran</div>
    </div>
    <div class="filter-chip {{ $summary['balance'] >= 0 ? '' : 'danger' }}">
        <div class="filter-chip-icon">{!! $icon('cashflow') !!}</div>
        <div class="filter-chip-count">{{ $rp($summary['balance']) }}</div>
        <div class="filter-chip-label">Balance</div>
        <div class="filter-chip-sub">{{ $summary['balance'] >= 0 ? 'Surplus' : 'Defisit' }}</div>
    </div>
</div>

<section class="card section wide">
    <div class="section-head">
        <h2>Cashflow Detail</h2>
        @if($can('admin', 'finance'))
        <a class="button ghost" href="{{ route('cashflows.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>
    <table>
        <thead><tr><th>Tanggal</th><th>Project</th><th>Type</th><th>Kategori</th><th>Amount</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($cashflowPages as $flow)
                <tr>
                    <td>{{ $flow->transaction_date }}</td>
                    <td class="font-bold">{{ $flow->project?->code ?? 'Company' }}</td>
                    <td><span class="badge badge-{{ $flow->type }}">{{ $flow->type }}</span></td>
                    <td>
                        <div>{{ $flow->category }}</div>
                        <div class="muted">{{ $flow->cost_type }}{{ $flow->vendor ? ' · '.$flow->vendor : '' }}</div>
                    </td>
                    <td class="{{ $flow->type === 'income' ? 'good' : 'bad' }} font-bold">{{ $rp($flow->amount) }}</td>
                    <td class="actions">
                        @if($can('admin', 'finance'))<a class="button mini ghost" href="{{ route('cashflows.edit-page', $flow) }}">Edit</a>@endif
                        @if($can('admin'))<form method="post" action="{{ route('cashflows.destroy', $flow) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="py-8 text-center text-slate-500">Belum ada cashflow.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($cashflowPages->hasPages())
        <div class="pager">
            <div class="text-sm text-slate-500">{{ $cashflowPages->firstItem() }}–{{ $cashflowPages->lastItem() }} dari {{ $cashflowPages->total() }}</div>
            {{ $cashflowPages->links() }}
        </div>
    @endif
</section>

@endsection
