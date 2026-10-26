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
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('vendor-bills.edit-page', $bill) }}"
                                        class="rounded p-1 text-slate-500 hover:bg-slate-100 hover:text-[#0369A1] transition-colors" title="Edit Tagihan">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </a>
                                    <button type="button"
                                        onclick="window.showDeleteModal('{{ route('vendor-bills.destroy', $bill) }}', 'Tagihan {{ $bill->bill_number }}')"
                                        class="rounded p-1 text-slate-400 hover:bg-red-50 hover:text-[#DC2626] transition-colors"
                                        title="Hapus Tagihan">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
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