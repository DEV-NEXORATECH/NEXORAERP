@extends('layouts.erp', ['activePage' => 'invoices', 'pageTitle' => 'Invoices'])

@section('content')
<div class="space-y-4">
    {{-- Card 1 — Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-5 rounded-lg border border-[#E5E7EB] shadow-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-semibold uppercase tracking-wider text-[#002875]">Finance</span>
                <span class="text-gray-300">/</span>
                <span class="text-xs font-semibold text-[#334155]">Invoices</span>
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

    {{-- Card 2 — Filter Panel --}}
    <div class="filter-panel">
        <div class="filter-panel-header">
            <div class="filter-panel-title">
                <div class="filter-panel-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </div>
                <h3>Filter & Pencarian</h3>
            </div>
        </div>
        <form id="invoice-filter" method="get" action="{{ route('finance.invoices.index') }}">
            <div class="filter-grid">
                <div class="filter-field sm:col-span-2">
                    <label for="search">Pencarian</label>
                    <input id="search" type="text" name="search" placeholder="Cari nomor invoice atau termin..." value="{{ request('search') }}">
                </div>
                <div class="filter-field">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">Semua Status</option>
                        <option value="draft" @selected(request('status')==='draft')>Draft</option>
                        <option value="sent" @selected(request('status')==='sent')>Sent</option>
                        <option value="partial" @selected(request('status')==='partial')>Partial</option>
                        <option value="paid" @selected(request('status')==='paid')>Paid</option>
                        <option value="void" @selected(request('status')==='void')>Void</option>
                    </select>
                </div>
                <div class="filter-field">
                    <label for="date_from">Dari Tanggal</label>
                    <input id="date_from" type="date" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="filter-field">
                    <label for="date_to">Sampai Tanggal</label>
                    <input id="date_to" type="date" name="date_to" value="{{ request('date_to') }}">
                </div>
            </div>
        </form>
        <div class="filter-actions">
            <div class="filter-actions-main">
                <button type="submit" form="invoice-filter" class="filter-btn-apply">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    Terapkan Filter
                </button>
                @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                    <a href="{{ route('finance.invoices.index') }}" class="filter-btn-reset">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                        Reset
                    </a>
                @endif
            </div>
        </div>
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
                        <tr class="hover:bg-[#F3F4F6] transition">
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
                                <x-erp.action-buttons
                                    :show-url="route('invoices.show', $invoice)"
                                    :edit-url="$can('admin', 'finance') ? route('invoices.edit-page', $invoice) : null"
                                    :print-url="route('invoices.pdf', $invoice)"
                                    :delete-url="$can('admin') ? route('invoices.destroy', $invoice) : null"
                                    :record-name="'faktur ' . $invoice->number"
                                    :can-edit="$can('admin', 'finance')"
                                    :can-delete="$can('admin')"
                                />
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

