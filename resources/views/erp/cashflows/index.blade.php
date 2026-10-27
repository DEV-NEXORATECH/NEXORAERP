@extends('layouts.erp', ['activePage' => 'cashflows', 'pageTitle' => 'Cashflow'])

@section('content')

{{-- ═══════════════════════ KPI Summary ═══════════════════════ --}}
<div class="report-summary-grid section">
    <div class="bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm">
        <div class="bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm-label">
            <svg class="inline-block h-4 w-4 mr-1 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
            Total Income
        </div>
        <div class="bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm-metric good">{{ $rp($summary['income']) }}</div>
        <div class="muted text-xs mt-1">pemasukan keseluruhan</div>
    </div>
    <div class="bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm">
        <div class="bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm-label">
            <svg class="inline-block h-4 w-4 mr-1 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/></svg>
            Total Expense
        </div>
        <div class="bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm-metric bad">{{ $rp($summary['expense']) }}</div>
        <div class="muted text-xs mt-1">pengeluaran keseluruhan</div>
    </div>
    <div class="bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm">
        <div class="bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm-label">
            <svg class="inline-block h-4 w-4 mr-1 {{ $summary['balance'] >= 0 ? 'text-emerald-500' : 'text-red-500' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 17 6-6 4 4 8-8"/><path d="M14 7h7v7"/></svg>
            Net Balance
        </div>
        <div class="bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm-metric {{ $summary['balance'] >= 0 ? 'good' : 'bad' }}">{{ $rp($summary['balance']) }}</div>
        <div class="muted text-xs mt-1">{{ $summary['balance'] >= 0 ? 'Surplus' : 'Defisit' }}</div>
    </div>
    <div class="bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm">
        <div class="bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm-label">
            <svg class="inline-block h-4 w-4 mr-1 text-[#002875]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>
            Transaksi
        </div>
        <div class="bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm-metric">{{ number_format($summary['count']) }}</div>
        <div class="muted text-xs mt-1">total entries</div>
    </div>
</div>

{{-- ═══════════════════════ Filter Panel ═══════════════════════ --}}
<section class="bg-white rounded-lg border border-[#D1D5DB] p-4 shadow-sm mb-4">
    <div class="filter-panel-header">
        <div class="filter-panel-icon">{!! $icon('cashflow') !!}</div>
        <div>
            <h3>Filter Cashflow</h3>
            <p class="muted">Saring berdasarkan periode, tipe, project, atau kata kunci.</p>
        </div>
    </div>
    
    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-panel">
        <form method="get" action="{{ route('cashflows.index-page') }}" class="filter-toolbar erp-filter-toolbar">
            
        <div class="filter-field xl:col-span-2">
            <label>Cari</label>
            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Deskripsi, kategori, vendor...">
        </div>
        <div class="filter-field">
            <label>Tahun</label>
            <select name="year">
                <option value="">Semua Tahun</option>
                @foreach($years as $y)
                    <option value="{{ $y }}" @selected(($filters['year'] ?? '') == $y)>{{ $y }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-field">
            <label>Bulan</label>
            <select name="month">
                <option value="">Semua Bulan</option>
                @foreach(['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'] as $i => $lbl)
                    <option value="{{ $i + 1 }}" @selected(($filters['month'] ?? '') == $i + 1)>{{ $lbl }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-field">
            <label>Tipe</label>
            <select name="type">
                <option value="">Semua</option>
                <option value="income" @selected(($filters['type'] ?? '') === 'income')>Income</option>
                <option value="expense" @selected(($filters['type'] ?? '') === 'expense')>Expense</option>
            </select>
        </div>
        <div class="filter-field">
            <label>Cost Type</label>
            <select name="cost_type">
                <option value="">Semua</option>
                @foreach($costTypes as $ct)
                    <option value="{{ $ct }}" @selected(($filters['cost_type'] ?? '') === $ct)>{{ str_replace('_', ' ', ucfirst($ct)) }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-field">
            <label>Project</label>
            <select name="project_id">
                <option value="">Semua Project</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" @selected(($filters['project_id'] ?? '') == $project->id)>{{ $project->code }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-field">
            <label>Bank / Kas</label>
            <select name="bank_account_id">
                <option value="">Semua Bank</option>
                @foreach($bankAccounts as $bank)
                    <option value="{{ $bank->id }}" @selected(($filters['bank_account_id'] ?? '') == $bank->id)>{{ $bank->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-actions xl:col-span-8">
            <button type="submit">Terapkan Filter</button>
            <a class="button ghost" href="{{ route('cashflows.index-page') }}">Reset</a>
            <a class="button ghost" href="{{ route('exports.cashflows') }}">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5"/><path d="M12 15V3"/></svg>
                Export CSV
            </a>
            @if($can('admin', 'finance'))
            <x-erp.toolbar-actions module="cashflows" :create-route="route('cashflows.create-page')" create-label="+ Tambah Cashflow" />
            @endif
        </div>
    
        </form>
    </div>

</section>

{{-- ═══════════════════════ Analytics ═══════════════════════ --}}
@if($summary['count'] > 0)
<section class="grid two section">

    {{-- Monthly Breakdown --}}
    <div class="card">
        <h3 class="mb-4">Bulanan {{ $year }}</h3>
        <table class="table-folk w-full">
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th class="text-right">Income</th>
                    <th class="text-right">Expense</th>
                    <th class="text-right">Net</th>
                </tr>
            </thead>
            <tbody>
                @foreach($monthlyBreakdown as $row)
                    @if($row['income'] > 0 || $row['expense'] > 0)
                    <tr>
                        <td class="font-bold">{{ $row['label'] }}</td>
                        <td class="good text-right">{{ $row['income'] > 0 ? $rp($row['income']) : '-' }}</td>
                        <td class="bad text-right">{{ $row['expense'] > 0 ? $rp($row['expense']) : '-' }}</td>
                        <td class="{{ $row['net'] >= 0 ? 'good' : 'bad' }} text-right font-bold">{{ $rp($row['net']) }}</td>
                    </tr>
                    @endif
                @endforeach
                @if(collect($monthlyBreakdown)->every(fn ($r) => $r['income'] == 0 && $r['expense'] == 0))
                    <tr><td colspan="4" class="py-6 text-center text-slate-400">Tidak ada data untuk tahun {{ $year }}.</td></tr>
                @endif
            </tbody>
            <tfoot>
                <tr class="border-t-2 border-[#d7e3ef]">
                    <td class="font-black">Total</td>
                    <td class="good text-right font-black">{{ $rp(collect($monthlyBreakdown)->sum('income')) }}</td>
                    <td class="bad text-right font-black">{{ $rp(collect($monthlyBreakdown)->sum('expense')) }}</td>
                    <td class="{{ collect($monthlyBreakdown)->sum('net') >= 0 ? 'good' : 'bad' }} text-right font-black">{{ $rp(collect($monthlyBreakdown)->sum('net')) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- By Cost Type + By Project stacked --}}
    <div class="flex flex-col gap-6">

        {{-- By Cost Type --}}
        <div class="card">
            <h3 class="mb-4">Per Cost Type</h3>
            <table class="table-folk w-full">
                <thead>
                    <tr><th>Tipe Biaya</th><th class="text-right">Income</th><th class="text-right">Expense</th><th class="text-right">Tx</th></tr>
                </thead>
                <tbody>
                    @forelse($byCostType as $ct => $row)
                    <tr>
                        <td>
                            <span class="inline-flex items-center rounded-lg bg-[#F4F7FB] px-2 py-0.5 text-xs font-bold text-[#002875]">
                                {{ str_replace('_', ' ', ucfirst($ct)) }}
                            </span>
                        </td>
                        <td class="good text-right text-sm">{{ $row['income'] > 0 ? $rp($row['income']) : '-' }}</td>
                        <td class="bad text-right text-sm">{{ $row['expense'] > 0 ? $rp($row['expense']) : '-' }}</td>
                        <td class="muted text-right text-sm">{{ $row['count'] }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-4 text-center text-slate-400">Tidak ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- By Project --}}
        <div class="card">
            <h3 class="mb-4">Per Project</h3>
            <table class="table-folk w-full">
                <thead>
                    <tr><th>Project</th><th class="text-right">Income</th><th class="text-right">Expense</th><th class="text-right">Tx</th></tr>
                </thead>
                <tbody>
                    @forelse($byProject as $code => $row)
                    <tr>
                        <td class="font-bold">{{ $code }}</td>
                        <td class="good text-right text-sm">{{ $row['income'] > 0 ? $rp($row['income']) : '-' }}</td>
                        <td class="bad text-right text-sm">{{ $row['expense'] > 0 ? $rp($row['expense']) : '-' }}</td>
                        <td class="muted text-right text-sm">{{ $row['count'] }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-4 text-center text-slate-400">Tidak ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</section>
@endif

{{-- ═══════════════════════ Transaction Table ═══════════════════════ --}}
<section class="card section wide">
    <div class="section-head">
        <div>
            <h2>Riwayat Transaksi</h2>
            @if(count($filters) && array_filter($filters))
                <p class="muted text-sm mt-0.5">Menampilkan {{ $cashflowPages->total() }} hasil dengan filter aktif.</p>
            @endif
        </div>
        @if($can('admin', 'finance'))
        <a class="button ghost" href="{{ route('cashflows.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="table-folk w-full">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Project</th>
                    <th>Tipe</th>
                    <th>Cost Type</th>
                    <th>Kategori</th>
                    <th>Deskripsi</th>
                    <th>Vendor</th>
                    <th>Bank / Kas</th>
                    <th class="text-right">Amount</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cashflowPages as $flow)
                <tr>
                    <td class="whitespace-nowrap">
                        <div class="font-bold text-sm">{{ \Carbon\Carbon::parse($flow->transaction_date)->format('d M Y') }}</div>
                        <div class="muted text-xs">{{ \Carbon\Carbon::parse($flow->transaction_date)->format('l') }}</div>
                    </td>
                    <td>
                        <span class="inline-flex items-center rounded-lg bg-[#F4F7FB] px-2 py-0.5 text-xs font-bold text-[#002875]">
                            {{ $flow->project?->code ?? 'Company' }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-{{ $flow->type }}">{{ ucfirst($flow->type) }}</span>
                    </td>
                    <td>
                        <span class="muted text-xs">{{ str_replace('_', ' ', ucfirst($flow->cost_type)) }}</span>
                    </td>
                    <td class="font-bold text-sm">{{ $flow->category }}</td>
                    <td class="muted text-sm max-w-[180px] truncate" title="{{ $flow->description }}">{{ $flow->description }}</td>
                    <td class="muted text-sm">{{ $flow->vendor ?: '-' }}</td>
                    <td class="muted text-sm">{{ $flow->bankAccount?->name ?? '-' }}</td>
                    <td class="text-right whitespace-nowrap">
                        @if($flow->type === 'income')
                            <span class="good font-black text-sm">+{{ $rp($flow->amount) }}</span>
                        @else
                            <span class="bad font-black text-sm">-{{ $rp($flow->amount) }}</span>
                        @endif
                    </td>
                    <td class="actions whitespace-nowrap">
                        <div class="inline-flex items-center gap-1.5">
                            <a data-action="print" href="{{ route('cashflows.pdf', $flow) }}" target="_blank" class="erp-action-button inline-flex h-7 w-7 items-center justify-center rounded border border-[#CBD5E1] bg-[#F8FAFC] text-[#475569] hover:bg-[#F1F5F9]" title="Cetak Voucher PDF">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            </a>
                            @if($can('admin', 'finance'))
                                <a data-action="edit" href="{{ route('cashflows.edit-page', $flow) }}" class="erp-action-button inline-flex h-7 w-7 items-center justify-center rounded border border-[#DDD6FE] bg-[#F5F3FF] text-[#7C3AED] hover:bg-[#EDE9FE]" title="Edit">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                        </a>
                            @endif
                            @if($can('admin'))
                                <form method="post" action="{{ route('cashflows.destroy', $flow) }}" class="inline inline erp-action-form" data-confirm-type="delete" data-confirm-title="Hapus data" data-confirm-text="Hapus transaksi ini?">
                            @csrf
                            @method('delete')
                            <button data-action="delete" type="submit" class="erp-action-button inline-flex h-7 w-7 items-center justify-center rounded border border-[#FECACA] bg-[#FEF2F2] text-[#DC2626] hover:bg-[#FEE2E2]" title="Hapus">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                            </button>
                        </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="py-12 text-center">
                        <div class="flex flex-col items-center gap-2 text-slate-400">
                            <svg class="h-10 w-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="m3 17 6-6 4 4 8-8"/><path d="M14 7h7v7"/></svg>
                            <p class="font-bold">Belum ada transaksi</p>
                            <p class="text-sm">Tambah cashflow pertama atau ubah filter.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <x-erp.pagination :paginator="$cashflowPages" />
</section>


<x-erp.import-modal module="cashflows" title="Impor Data Cashflows" />
@endsection
