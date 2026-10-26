@extends('layouts.erp', ['activePage' => 'vendor-bills', 'pageTitle' => 'Tagihan Vendor (AP)'])

@section('content')
<div class="space-y-4">
    {{-- Card 1 — Page Header & Global Actions --}}
    <x-erp.page-header
        title="Tagihan Pemasok / Vendor Bills (AP)"
        subtitle="Kelola kewajiban utang dagang, verifikasi kesesuaian pesanan barang/jasa, dan riwayat pembayaran vendor."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Finance & Accounting', 'url' => route('finance-suite.index')], ['label' => 'Vendor Bills']]"
    >
        <x-slot:actions>
            <x-erp.toolbar-actions module="vendor-bills" :create-route="route('vendor-bills.create-page')" create-label="Tambah Tagihan Vendor" />
        </x-slot:actions>
    </x-erp.page-header>

    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-card">
        <form method="get" action="{{ route('vendor-bills.index') }}" class="filter-toolbar">
            <input type="text" name="search" placeholder="Cari nomor tagihan, vendor..." value="{{ request('search') }}" class="filter-search">
            <select name="status" class="filter-select">
                <option value="">Semua Status</option>
                <option value="unpaid" @selected(request('status') == 'unpaid')>Belum Dibayar (Unpaid)</option>
                <option value="partial" @selected(request('status') == 'partial')>Sebagian (Partial)</option>
                <option value="paid" @selected(request('status') == 'paid')>Lunas (Paid)</option>
                <option value="void" @selected(request('status') == 'void')>Batal (Void)</option>
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="filter-date">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="filter-date">
            <div class="flex items-center gap-2">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-[#001B50] transition-colors">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                    <a href="{{ route('vendor-bills.index') }}" class="inline-flex items-center rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-y border-slate-200 bg-slate-50/75 text-[11px] font-semibold text-slate-600 uppercase tracking-wider">
                        <th class="py-3 px-4">Nomor Tagihan</th>
                        <th class="py-3 px-4">Vendor</th>
                        <th class="py-3 px-4">Tgl Tagihan</th>
                        <th class="py-3 px-4">Jatuh Tempo</th>
                        <th class="py-3 px-4 text-right">Total Tagihan</th>
                        <th class="py-3 px-4 text-right">Sisa Hutang</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-800">
                    @forelse($vendorBills as $bill)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3 px-4 font-mono font-bold text-[#002875]">{{ $bill->bill_number }}</td>
                            <td class="py-3 px-4 font-semibold text-slate-900">{{ $bill->vendor->name ?? '-' }}</td>
                            <td class="py-3 px-4 text-slate-600 whitespace-nowrap">{{ $bill->bill_date ? \Carbon\Carbon::parse($bill->bill_date)->format('d/m/Y') : '-' }}</td>
                            <td class="py-3 px-4 text-slate-600 whitespace-nowrap">{{ $bill->due_date ? \Carbon\Carbon::parse($bill->due_date)->format('d/m/Y') : '-' }}</td>
                            <td class="py-3 px-4 text-right font-bold text-slate-900">{{ $bill->currency_code ?? 'IDR' }} {{ number_format($bill->total_amount, 2, ',', '.') }}</td>
                            <td class="py-3 px-4 text-right font-bold {{ $bill->remaining_balance > 0 ? 'text-red-600' : 'text-slate-500' }}">
                                {{ $bill->currency_code ?? 'IDR' }} {{ number_format($bill->remaining_balance, 2, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <x-erp.status-badge :status="$bill->status" />
                            </td>
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('vendor-bills.edit-page', $bill) }}" class="inline-flex h-7 w-7 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE]" title="Edit">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                    </a>
                                    <a href="{{ route('vendor-bills.pdf', $bill) }}" target="_blank" class="inline-flex h-7 w-7 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE]" title="Cetak PDF">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                                    </a>
                                    <form method="post" action="{{ route('vendor-bills.destroy', $bill) }}" class="inline" onsubmit="return confirm('Hapus tagihan ini?');">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="inline-flex h-7 w-7 items-center justify-center rounded border border-[#FECACA] bg-[#FEF2F2] text-[#DC2626] hover:bg-[#FEE2E2]" title="Hapus">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <x-erp.empty-state message="Belum ada data tagihan vendor." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Card 4 — Standalone Pagination --}}
    <x-erp.pagination :paginator="$vendorBills" />
</div>

<x-erp.confirm-dialog />
@endsection