@extends('layouts.erp', ['activePage' => 'invoices', 'pageTitle' => 'Invoices'])

@section('content')
<div class="space-y-4">
    {{-- Card 1 — Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-5 rounded-lg border border-[#E5E7EB] shadow-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-semibold uppercase tracking-wider text-[#002875]">Finance</span>
                <span class="text-gray-300">/</span>
                <span class="text-xs font-semibold text-gray-700">Invoices</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#0F172A]">Faktur Penjualan (Invoices)</h1>
            <p class="mt-0.5 text-xs text-[#64748B]">Kelola data tagihan, termin proyek, pemantauan piutang, dan status pembayaran klien.</p>
        </div>
        <x-erp.toolbar-actions 
            module="invoices" 
            :create-route="route('invoices.create-page')" 
            create-label="+ Buat Invoice" 
            :can-create="$can('admin', 'finance')" 
        />
    </div>

    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-card">
        <form method="get" action="{{ route('finance.invoices.index') }}" class="filter-toolbar">
            <div class="relative w-64 flex-shrink-0">
                <input type="text" name="search" placeholder="Cari invoice atau termin..." value="{{ request('search') }}" class="filter-search pl-8 pr-3 text-xs">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-2.5 text-[#9CA3AF]">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </div>
            </div>

            <select name="status" class="text-xs">
                <option value="">Semua Status</option>
                <option value="draft" @selected(request('status')==='draft')>Draft</option>
                <option value="sent" @selected(request('status')==='sent')>Sent</option>
                <option value="partial" @selected(request('status')==='partial')>Partial</option>
                <option value="paid" @selected(request('status')==='paid')>Paid</option>
                <option value="void" @selected(request('status')==='void')>Void</option>
            </select>

            <input type="date" name="date_from" value="{{ request('date_from') }}" class="text-xs" title="Dari Tanggal">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="text-xs" title="Sampai Tanggal">

            <button type="submit" class="inline-flex h-[34px] items-center justify-center rounded-md bg-[#002875] px-3.5 text-xs font-semibold text-white hover:bg-[#001B50] transition shadow-xs cursor-pointer flex-shrink-0">
                Filter
            </button>

            @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                <a href="{{ route('finance.invoices.index') }}" class="inline-flex h-[34px] items-center justify-center rounded-md border border-[#D1D5DB] bg-white px-3 text-xs font-semibold text-[#475569] hover:bg-[#F3F4F6] transition shadow-xs flex-shrink-0">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Card 3 — Workspace: Data Table + Pagination --}}
    <div class="workspace-card bg-white rounded-lg border border-[#E5E7EB] shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead class="bg-[#F8FAFC] border-b border-[#E5E7EB]">
                    <tr>
                        <th class="px-4 py-3 font-semibold text-[#475569]">Nomor Faktur</th>
                        <th class="px-4 py-3 font-semibold text-[#475569]">Proyek / Jatuh Tempo</th>
                        <th class="px-4 py-3 font-semibold text-[#475569] text-center">Status</th>
                        <th class="px-4 py-3 font-semibold text-[#475569] text-right">Terbayar / Total Tagihan</th>
                        <th class="px-4 py-3 font-semibold text-[#475569] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @forelse($invoicesPage as $invoice)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3">
                                <a href="{{ route('invoices.show', $invoice) }}" class="font-bold text-[#002875] hover:underline">
                                    {{ $invoice->number }}
                                </a>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-[#0F172A]">{{ $invoice->project->code ?? 'Umum' }}</div>
                                <div class="text-[11px] text-[#64748B]">Jatuh tempo: {{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') : '-' }}</div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                    {{ $invoice->status === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($invoice->status === 'partial' ? 'bg-amber-50 text-amber-700 border border-amber-200' : ($invoice->status === 'void' ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-blue-50 text-[#002875] border border-blue-200')) }}">
                                    {{ $invoice->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="font-bold text-[#15803D]">{{ $rp($invoice->paid_amount) }}</div>
                                <div class="text-[11px] text-[#64748B]">Total: {{ $rp($invoice->amount) }}</div>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    {{-- Eye / Show --}}
                                    <a href="{{ route('invoices.show', $invoice) }}" class="inline-flex h-7 w-7 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE]" title="Lihat Detail">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>

                                    {{-- Edit --}}
                                    @if($can('admin', 'finance'))
                                        <a href="{{ route('invoices.edit-page', $invoice) }}" class="inline-flex h-7 w-7 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE]" title="Edit">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                        </a>
                                    @endif

                                    {{-- Print / PDF --}}
                                    <a href="{{ route('invoices.pdf', $invoice) }}" target="_blank" class="inline-flex h-7 w-7 items-center justify-center rounded border border-[#D1D5DB] bg-white text-[#475569] hover:bg-[#F3F4F6] hover:text-[#002875]" title="Cetak PDF">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                                    </a>

                                    {{-- Delete --}}
                                    @if($can('admin'))
                                        <form method="post" action="{{ route('invoices.destroy', $invoice) }}" class="inline" onsubmit="return confirm('Hapus faktur {{ $invoice->number }}? Tindakan ini tidak dapat dibatalkan.');">
                                            @csrf @method('delete')
                                            <button type="submit" class="inline-flex h-7 w-7 items-center justify-center rounded border border-[#FECACA] bg-[#FEF2F2] text-[#DC2626] hover:bg-[#FEE2E2]" title="Hapus">
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-[#94A3B8]">Belum ada data invoice terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Card 4 — Pagination (Card Terpisah) --}}
    <x-erp.pagination :paginator="$invoicesPage" />
</div>

<x-erp.import-modal module="invoices" title="Impor Data Invoices" />
@endsection

