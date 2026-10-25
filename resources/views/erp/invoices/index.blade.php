@extends('layouts.erp', ['activePage' => 'invoices', 'pageTitle' => 'Invoice & Payment'])

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Faktur Penjualan (Invoices)</h1>
            <p class="text-xs text-gray-500">Kelola master data dan transaksi Faktur Penjualan (Invoices)</p>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
        <form method="get" action="{{ route('finance.index') }}" class="p-3 border-b border-gray-200 bg-gray-50/50 flex flex-wrap items-center gap-2.5">
            
        <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="px-2.5 py-1.5 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white">
        <select name="status" class="px-2.5 py-1.5 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white">
            <option value="">Semua Status</option>
            <option value="draft" @selected(request('status')==='draft')>Draft</option>
            <option value="sent" @selected(request('status')==='sent')>Sent</option>
            <option value="partial" @selected(request('status')==='partial')>Partial</option>
            <option value="paid" @selected(request('status')==='paid')>Paid</option>
            <option value="void" @selected(request('status')==='void')>Void</option>
        </select>
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="px-2.5 py-1.5 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white">
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="px-2.5 py-1.5 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white">
        <div class="flex gap-2 items-end">
            <button class="px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition">Filter</button>
            <a href="{{ route('finance.index') }}" class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Reset</a>
        </div>
    
        </form>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Invoice</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Status</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Terbayar / Total</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    
            @forelse($invoicesPage as $invoice)
                <tr>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">
                        <div class="font-black">{{ $invoice->number }}</div>
                        <div class="muted">{{ $invoice->project->code }} · due {{ $invoice->due_date }}</div>
                    </td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left"><span class="badge badge-{{ $invoice->status }}">{{ $invoice->status }}</span></td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">
                        <div class="good font-bold">{{ $rp($invoice->paid_amount) }}</div>
                        <div class="muted">dari {{ $rp($invoice->amount) }}</div>
                    </td>
                    <td class="px-3 py-2 text-xs text-right whitespace-nowrap">
                        <a class="button mini ghost" href="{{ route('invoices.pdf', $invoice) }}" target="_blank">Lihat</a>
                        @if($can('admin', 'finance'))<a class="button mini ghost" href="{{ route('invoices.edit-page', $invoice) }}">Edit</a>@endif
                        @if($can('admin'))<form method="post" action="{{ route('invoices.destroy', $invoice) }}">@csrf @method('delete')<button class="inline-flex items-center px-2 py-1 text-[11px] font-medium text-red-700 bg-red-50 border border-red-200 rounded hover:bg-red-100 transition">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="py-8 text-center text-slate-500">Belum ada invoice.</td></tr>
            @endforelse
        
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
