@extends('layouts.erp', ['activePage' => 'invoices', 'pageTitle' => 'Invoice '.$invoice->number])

@section('content')
@php
    $totalPaid = $invoice->payments->sum('amount');
    $taxAmount = ($invoice->tax_rate ? ($invoice->amount * $invoice->tax_rate / 100) : 0);
    $grandTotal = $invoice->amount + $taxAmount;
    $balance = max(0, $grandTotal - $totalPaid);
@endphp

<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3.5">
            <a href="{{ route('finance.invoices.index') }}" class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-md border border-[#E5E7EB] bg-white text-[#475569] transition hover:border-[#002875] hover:text-[#002875]" title="Kembali">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-[#002875]">Finance</span>
                    <span class="text-[#475569]">·</span>
                    <span class="text-[11px] font-medium text-[#475569]">Invoices</span>
                    <span class="text-[#475569]">·</span>
                    <span class="text-[11px] font-bold text-[#0F172A]">{{ $invoice->number }}</span>
                </div>
                <div class="flex items-center gap-2.5 mt-0.5">
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#0F172A]">{{ $invoice->number }}</h1>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold uppercase
                        {{ $invoice->status === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($invoice->status === 'partial' ? 'bg-amber-50 text-amber-700 border border-amber-200' : ($invoice->status === 'void' ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-blue-50 text-[#002875] border border-blue-200')) }}">
                        {{ $invoice->status }}
                    </span>
                </div>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('invoices.pdf', $invoice) }}" target="_blank" class="btn btn-outline inline-flex h-9 items-center gap-1.5 px-3.5 text-xs font-semibold shadow-xs">
                <svg class="h-4 w-4 text-[#002875]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak PDF
            </a>
            <a href="{{ route('invoices.edit-page', $invoice) }}" class="inline-flex h-9 items-center gap-1.5 px-3.5 rounded-md text-xs font-semibold text-white bg-[#0369A1] hover:bg-[#0284C7] transition shadow-xs">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit Invoice
            </a>
        </div>
    </div>

    {{-- Financial Summary Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-lg border border-[#E5E7EB] shadow-xs">
            <div class="text-[11px] font-semibold uppercase tracking-wider text-[#475569]">Subtotal Tagihan</div>
            <div class="text-2xl font-bold text-[#0F172A] mt-1">{{ $rp($invoice->amount) }}</div>
            <div class="text-xs text-[#64748B] mt-0.5">Nilai DPP tagihan proyek</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-[#E5E7EB] shadow-xs">
            <div class="text-[11px] font-semibold uppercase tracking-wider text-[#475569]">Pajak (PPN {{ $invoice->tax_rate ?? 0 }}%)</div>
            <div class="text-2xl font-bold text-[#0F172A] mt-1">{{ $rp($taxAmount) }}</div>
            <div class="text-xs text-[#64748B] mt-0.5">Total beban pajak</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-[#E5E7EB] shadow-xs">
            <div class="text-[11px] font-semibold uppercase tracking-wider text-[#475569]">Total Terbayar</div>
            <div class="text-2xl font-bold text-[#15803D] mt-1">{{ $rp($totalPaid) }}</div>
            <div class="text-xs text-[#64748B] mt-0.5">{{ $invoice->payments->count() }} transaksi pembayaran</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-[#E5E7EB] shadow-xs">
            <div class="text-[11px] font-semibold uppercase tracking-wider text-[#475569]">Sisa Piutang</div>
            <div class="text-2xl font-bold {{ $balance > 0 ? 'text-[#DC2626]' : 'text-emerald-700' }} mt-1">{{ $rp($balance) }}</div>
            <div class="text-xs text-[#64748B] mt-0.5">{{ $balance > 0 ? 'Belum lunas' : 'Sudah lunas penuh' }}</div>
        </div>
    </div>

    {{-- Details 2 Columns --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Card 1: Invoice Information --}}
        <div class="rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs">
            <h2 class="text-sm font-bold text-[#0F172A] border-b border-[#E5E7EB] pb-3 mb-4">Informasi Dokumen Faktur</h2>
            <dl class="grid grid-cols-2 gap-x-4 gap-y-3 text-xs">
                <div>
                    <dt class="text-[#64748B]">Proyek Terkait</dt>
                    <dd class="font-bold text-[#0F172A] mt-0.5">{{ $invoice->project->code ?? '-' }} — {{ $invoice->project->name ?? 'Proyek Umum' }}</dd>
                </div>
                <div>
                    <dt class="text-[#64748B]">Klien / Customer</dt>
                    <dd class="font-bold text-[#0F172A] mt-0.5">{{ $invoice->project->clientRecord->name ?? $invoice->project->client ?? 'Umum' }}</dd>
                </div>
                <div>
                    <dt class="text-[#64748B]">Tanggal Terbit</dt>
                    <dd class="font-semibold text-[#0F172A] mt-0.5">{{ $invoice->issue_date ? \Carbon\Carbon::parse($invoice->issue_date)->format('d M Y') : '-' }}</dd>
                </div>
                <div>
                    <dt class="text-[#64748B]">Jatuh Tempo</dt>
                    <dd class="font-semibold text-[#0F172A] mt-0.5">{{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') : '-' }}</dd>
                </div>
                <div>
                    <dt class="text-[#64748B]">Syarat Pembayaran</dt>
                    <dd class="font-medium text-[#0F172A] mt-0.5">{{ $invoice->payment_terms ?: 'Net 30 Days' }}</dd>
                </div>
                <div>
                    <dt class="text-[#64748B]">Proposal Terkait</dt>
                    <dd class="font-medium text-[#0F172A] mt-0.5">{{ $invoice->proposal->number ?? '-' }}</dd>
                </div>
                <div class="col-span-2 pt-2 border-t border-[#E5E7EB]">
                    <dt class="text-[#64748B]">Catatan Faktur</dt>
                    <dd class="font-normal text-[#334155] mt-1">{{ $invoice->notes ?: 'Tidak ada catatan khusus.' }}</dd>
                </div>
            </dl>
        </div>

        {{-- Card 2: Payment History --}}
        <div class="rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-[#E5E7EB] mb-4">
                    <h2 class="text-sm font-bold text-[#0F172A]">Riwayat Pembayaran (Payment History)</h2>
                    <span class="text-xs font-semibold text-[#002875]">{{ $invoice->payments->count() }} Pembayaran</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="table-folk w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-[#E5E7EB] bg-[#F8FAFC]">
                                <th class="p-2 font-semibold text-[#475569]">Tanggal</th>
                                <th class="p-2 font-semibold text-[#475569]">Rekening Tujuan</th>
                                <th class="p-2 font-semibold text-[#475569] text-right">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E5E7EB]">
                            @forelse($invoice->payments as $payment)
                                <tr>
                                    <td class="p-2 text-[#0F172A] font-medium">{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') : '-' }}</td>
                                    <td class="p-2 text-[#475569]">{{ $payment->bankAccount->name ?? 'Kas / Bank' }}</td>
                                    <td class="p-2 text-right font-bold text-[#15803D]">{{ $rp($payment->amount) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="p-6 text-center text-[#94A3B8]">Belum ada data pembayaran untuk invoice ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($balance > 0)
                <div class="mt-4 pt-3 border-t border-[#E5E7EB] flex items-center justify-between">
                    <span class="text-xs text-[#64748B]">Tagihan ini masih memiliki outstanding saldo.</span>
                    <a href="{{ route('payments.create-page', ['invoice_id' => $invoice->id]) }}" class="btn btn-primary inline-flex h-8 items-center px-3 text-xs font-semibold">
                        + Catat Pembayaran
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection