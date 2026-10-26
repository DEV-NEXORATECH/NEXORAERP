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
    <div class="filter-card">
        <form method="get" action="{{ route('vendor-payments.index') }}" class="filter-toolbar">
            <input type="text" name="search" placeholder="Cari referensi, nomor tagihan..." value="{{ request('search') }}" class="filter-search">
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="filter-date">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="filter-date">
            <div class="flex items-center gap-2">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-[#001B50] transition-colors">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'date_from', 'date_to']))
                    <a href="{{ route('vendor-payments.index') }}" class="inline-flex items-center rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
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
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3 px-4 text-slate-600 whitespace-nowrap">{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') : '-' }}</td>
                            <td class="py-3 px-4 font-mono font-bold text-[#002875]">{{ $payment->vendorBill->bill_number ?? '-' }}</td>
                            <td class="py-3 px-4 text-slate-700">{{ $payment->bankAccount->name ?? 'Kas / Bank Utama' }}</td>
                            <td class="py-3 px-4 text-right font-bold text-slate-900">{{ $payment->currency_code ?? 'IDR' }} {{ number_format($payment->amount, 2, ',', '.') }}</td>
                            <td class="py-3 px-4 text-slate-500 font-mono text-[11px]">{{ $payment->reference ?? '-' }}</td>
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1">
                                    <button type="button"
                                        onclick="window.showDeleteModal('{{ route('vendor-payments.destroy', $payment) }}', 'Pembayaran {{ $payment->vendorBill->bill_number ?? '' }} ({{ $payment->payment_date }})')"
                                        class="rounded p-1 text-slate-400 hover:bg-red-50 hover:text-[#DC2626] transition-colors"
                                        title="Hapus Pembayaran">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
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