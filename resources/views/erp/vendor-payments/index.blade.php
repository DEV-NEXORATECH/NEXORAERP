@extends('layouts.erp', ['activePage' => 'vendor-payments', 'pageTitle' => 'Pembayaran Vendor (AP)'])

@section('content')
<div class="space-y-4">
    {{-- Card 1 — Page Header & Global Actions --}}
    <x-erp.page-header
        title="Pembayaran Tagihan Vendor (AP Payments)"
        subtitle="Pencatatan realisasi pembayaran utang dagang ke rekening bank/kas vendor serta nomor referensi transfer."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Finance & Accounting', 'url' => route('finance-suite.index')], ['label' => 'Vendor Payments']]"
    >
        <x-slot:actions>
            <x-erp.toolbar-actions module="vendor-payments" :create-route="route('vendor-payments.create-page')" create-label="Catat Pembayaran Vendor" />
        </x-slot:actions>
    </x-erp.page-header>

    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-panel">
        <div class="filter-panel-header">
            <div class="filter-panel-title">
                <div class="filter-panel-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg></div>
                <h3>Filter & Pencarian</h3>
            </div>
        </div>
        <form method="get" action="{{ route('vendor-payments.index') }}" class="filter-toolbar erp-filter-toolbar">
            <input type="text" name="search" placeholder="Cari referensi, nomor tagihan..." value="{{ request('search') }}" class="filter-search">
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="filter-date">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="filter-date">
            <div class="flex items-center gap-2">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-[#001B50] transition-colors">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'date_from', 'date_to']))
                    <a href="{{ route('vendor-payments.index') }}" class="inline-flex items-center rounded-md border border-[#D1D5DB] bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-[#F3F4F6] transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-[#D1D5DB] bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-y border-[#D1D5DB] bg-slate-50/75 text-[11px] font-semibold text-slate-600 uppercase tracking-wider">
                        <th class="py-3 px-4">Tanggal Bayar</th>
                        <th class="py-3 px-4">Tagihan Vendor (Bill #)</th>
                        <th class="py-3 px-4">Rekening Bank / Kas Pengirim</th>
                        <th class="py-3 px-4 text-right">Nominal Keluar</th>
                        <th class="py-3 px-4">Referensi / No Resi</th>
                        <th class="py-3 px-4 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-800">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-[#F3F4F6]/60 transition-colors">
                            <td class="py-3 px-4 text-slate-600 whitespace-nowrap">{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') : '-' }}</td>
                            <td class="py-3 px-4 font-mono font-bold text-[#002875]">{{ $payment->vendorBill->bill_number ?? '-' }}</td>
                            <td class="py-3 px-4 text-slate-700">{{ $payment->bankAccount->name ?? 'Kas / Bank Utama' }}</td>
                            <td class="py-3 px-4 text-right font-bold text-slate-900">{{ $payment->currency_code ?? 'IDR' }} {{ number_format($payment->amount, 2, ',', '.') }}</td>
                            <td class="py-3 px-4 text-slate-500 font-mono text-[11px]">{{ $payment->reference ?? '-' }}</td>
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <a data-action="print" href="{{ route('vendor-payments.pdf', $payment) }}" target="_blank" class="erp-action-button inline-flex h-7 w-7 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE]" title="Cetak Voucher PDF">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                                    </a>
                                    <form method="post" action="{{ route('vendor-payments.destroy', $payment) }}" class="inline inline erp-action-form" data-confirm-type="delete" data-confirm-title="Hapus data" data-confirm-text="Hapus pembayaran ini?">
                                        @csrf
                                        @method('delete')
                                        <button data-action="delete" type="submit" class="erp-action-button inline-flex h-7 w-7 items-center justify-center rounded border border-[#FECACA] bg-[#FEF2F2] text-[#DC2626] hover:bg-[#FEE2E2]" title="Hapus">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <x-erp.empty-state message="Belum ada data pembayaran vendor." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Card 4 — Standalone Pagination --}}
    <x-erp.pagination :paginator="$payments" />
</div>

<x-erp.confirm-dialog />
@endsection