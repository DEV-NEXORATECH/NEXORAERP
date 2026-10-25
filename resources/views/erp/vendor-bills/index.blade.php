@extends('layouts.erp', ['activePage' => 'vendor-bills', 'pageTitle' => 'Tagihan Vendor (AP)'])

@section('content')
<div class="space-y-6">
    <x-erp.page-header
        title="Tagihan Pemasok / Vendor Bills (AP)"
        subtitle="Kelola kewajiban utang dagang, verifikasi kesesuaian pesanan barang/jasa, dan riwayat pembayaran vendor."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Finance & Accounting', 'url' => route('finance-suite.index')], ['label' => 'Vendor Bills']]"
    >
        <x-slot:actions>
            <x-erp.toolbar-actions module="vendor-bills" :create-route="route('vendor-bills.create-page')" create-label="+ Tambah Tagihan Vendor" />
        </x-slot:actions>
    </x-erp.page-header>

    <div class="rounded-lg border border-slate-200 bg-white shadow-sm overflow-hidden">
        <x-erp.toolbar
            :search="request('search')"
            searchPlaceholder="Cari nomor tagihan, vendor..."
            :resetUrl="route('vendor-bills.index')"
        >
            <x-slot:filters>
                <select name="status" class="rounded-md border border-slate-300 bg-white px-2.5 py-1.5 text-xs text-slate-700 focus:border-[#002875] focus:outline-none">
                    <option value="">Semua Status</option>
                    <option value="unpaid" @selected(request('status') == 'unpaid')>Belum Dibayar (Unpaid)</option>
                    <option value="partial" @selected(request('status') == 'partial')>Sebagian (Partial)</option>
                    <option value="paid" @selected(request('status') == 'paid')>Lunas (Paid)</option>
                    <option value="void" @selected(request('status') == 'void')>Batal (Void)</option>
                </select>
                <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="Dari tgl"
                    class="rounded-md border border-slate-300 bg-white px-2.5 py-1.5 text-xs text-slate-700 focus:border-[#002875] focus:outline-none">
                <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="Sampai tgl"
                    class="rounded-md border border-slate-300 bg-white px-2.5 py-1.5 text-xs text-slate-700 focus:border-[#002875] focus:outline-none">
            </x-slot:filters>
        </x-erp.toolbar>

        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-y border-slate-200 bg-slate-50/75 text-[11px] font-semibold text-slate-600 uppercase tracking-wider">
                        <th class="py-3 px-4">Nomor Tagihan</th>
                        <th class="py-3 px-4">Vendor / Pemasok</th>
                        <th class="py-3 px-4">Alokasi Project</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Sudah Dibayar</th>
                        <th class="py-3 px-4 text-right">Total Tagihan</th>
                        <th class="py-3 px-4 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-800">
                    @forelse($vendorBills as $bill)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3 px-4 font-bold text-[#002875]">{{ $bill->bill_number }}</td>
                            <td class="py-3 px-4 font-medium text-slate-900">{{ $bill->vendor_name }}</td>
                            <td class="py-3 px-4">
                                @if($bill->project)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-blue-50 text-[#002875]">
                                        {{ $bill->project->code }}
                                    </span>
                                @else
                                    <span class="text-slate-400">Company (Overhead)</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                <x-erp.status-badge :status="$bill->status" />
                            </td>
                            <td class="py-3 px-4 text-right font-semibold text-[#15803D] whitespace-nowrap">{{ $rp($bill->paid_amount) }}</td>
                            <td class="py-3 px-4 text-right font-bold text-slate-900 whitespace-nowrap">{{ $rp($bill->amount) }}</td>
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1">
                                    @if($can('admin', 'finance'))
                                    <a href="{{ route('vendor-bills.edit-page', $bill) }}"
                                        class="rounded p-1 text-slate-500 hover:bg-slate-100 hover:text-[#0369A1] transition-colors" title="Edit Tagihan">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </a>
                                    @endif
                                    @if($can('admin'))
                                    <button type="button"
                                        onclick="window.showDeleteModal('{{ route('vendor-bills.destroy', $bill) }}', 'Tagihan Vendor {{ $bill->bill_number }} ({{ $bill->vendor_name }})')"
                                        class="rounded p-1 text-slate-400 hover:bg-red-50 hover:text-[#DC2626] transition-colors"
                                        title="Hapus Tagihan">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <x-erp.empty-state message="Belum ada data tagihan vendor (AP)." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($vendorBills->hasPages())
        <div class="pagination-card flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-lg border border-[#E5E7EB] bg-white px-4 py-3 text-xs text-[#475569] shadow-xs gap-3">
            <div>
                    Menampilkan <strong>{{ $vendorBills->firstItem() ?? 0 }}</strong> - <strong>{{ $vendorBills->lastItem() ?? 0 }}</strong> dari <strong>{{ $vendorBills->total() }}</strong> tagihan
                </div>
                <div>
                    {{ $vendorBills->links() }}
                </div>
        </div>
    @endif
</div>

<x-erp.confirm-dialog />

<x-erp.import-modal module="vendor-bills" title="Impor Data Vendor Bills" />
@endsection