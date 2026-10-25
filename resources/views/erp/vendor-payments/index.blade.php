@extends('layouts.erp', ['activePage' => 'vendor-payments', 'pageTitle' => 'Pembayaran Vendor (AP)'])

@section('content')
<div class="space-y-6">
    <x-erp.page-header
        title="Pembayaran Tagihan Vendor (AP Payments)"
        subtitle="Pencatatan realisasi pembayaran utang dagang ke rekening bank/kas vendor serta nomor referensi transfer."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Finance & Accounting', 'url' => route('finance-suite.index')], ['label' => 'Vendor Payments']]"
    >
        <x-slot:actions>
            @if($can('admin', 'finance'))
            <a href="{{ route('vendor-payments.create-page') }}" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-3.5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-[#001B50] focus:outline-none focus:ring-2 focus:ring-[#002875] transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Catat Pembayaran Vendor
            </a>
            @endif
        </x-slot:actions>
    </x-erp.page-header>

    <div class="rounded-lg border border-slate-200 bg-white shadow-sm overflow-hidden">
        <x-erp.toolbar
            :search="request('search')"
            searchPlaceholder="Cari referensi, nomor tagihan..."
            :resetUrl="route('vendor-payments.index')"
        >
            <x-slot:filters>
                <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="Dari tgl"
                    class="rounded-md border border-slate-300 bg-white px-2.5 py-1.5 text-xs text-slate-700 focus:border-[#002875] focus:outline-none">
                <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="Sampai tgl"
                    class="rounded-md border border-slate-300 bg-white px-2.5 py-1.5 text-xs text-slate-700 focus:border-[#002875] focus:outline-none">
            </x-slot:filters>
        </x-erp.toolbar>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
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
                            <td class="py-3 px-4">
                                <span class="font-bold text-[#002875]">{{ $payment->vendorBill?->bill_number ?? '-' }}</span>
                                <div class="text-[11px] text-slate-500 font-medium">{{ $payment->vendorBill?->vendor_name ?? '' }}</div>
                            </td>
                            <td class="py-3 px-4 text-slate-700 font-medium">
                                {{ $payment->bankAccount?->name ?? 'Kas Umum / Bank' }}
                            </td>
                            <td class="py-3 px-4 text-right font-bold text-[#DC2626] whitespace-nowrap">
                                {{ $rp($payment->amount) }}
                            </td>
                            <td class="py-3 px-4 text-slate-600 font-mono text-[11px]">
                                {{ $payment->reference ?: '-' }}
                            </td>
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1">
                                    @if($can('admin', 'finance'))
                                    <a href="{{ route('vendor-payments.edit-page', $payment) }}"
                                        class="rounded p-1 text-slate-500 hover:bg-slate-100 hover:text-[#0369A1] transition-colors" title="Edit Pembayaran">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </a>
                                    @endif
                                    @if($can('admin'))
                                    <button type="button"
                                        onclick="window.showDeleteModal('{{ route('vendor-payments.destroy', $payment) }}', 'Pembayaran Vendor {{ $payment->reference ?: 'AP' }}')"
                                        class="rounded p-1 text-slate-400 hover:bg-red-50 hover:text-[#DC2626] transition-colors"
                                        title="Hapus Pembayaran">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <x-erp.empty-state message="Belum ada riwayat pembayaran vendor." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
            <div class="border-t border-slate-200 bg-slate-50/50 px-4 py-3 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
                <div>
                    Menampilkan <strong>{{ $payments->firstItem() ?? 0 }}</strong> - <strong>{{ $payments->lastItem() ?? 0 }}</strong> dari <strong>{{ $payments->total() }}</strong> pembayaran
                </div>
                <div>
                    {{ $payments->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<x-erp.confirm-dialog />
@endsection
