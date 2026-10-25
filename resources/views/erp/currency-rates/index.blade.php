@extends('layouts.erp', ['activePage' => 'currency-rates.index', 'pageTitle' => 'Currency Rates'])

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Kurs Mata Uang (Exchange Rates)</h1>
            <p class="text-xs text-gray-500">Kelola master data dan transaksi Kurs Mata Uang (Exchange Rates)</p>
        </div>
        <x-erp.toolbar-actions module="currency-rates" :create-route="route('currency-rates.create-page')" create-label="+ Input Kurs" />
    </div>

    <!-- Table Card -->
    
    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-card">
        <form method="get" action="{{ route('currency-rates.index') }}" class="filter-toolbar">
            
            
        <input type="text" name="from_currency" placeholder="Dari Mata Uang..." value="{{ request('from_currency') }}" class="px-2.5 py-1.5 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white">
        <input type="text" name="to_currency" placeholder="Ke Mata Uang..." value="{{ request('to_currency') }}" class="px-2.5 py-1.5 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white">
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="px-2.5 py-1.5 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white">
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="px-2.5 py-1.5 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white">
        <div class="flex gap-2 items-end">
            <button class="px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition">Filter</button>
            <a href="{{ route('currency-rates.index') }}" class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Reset</a>
        </div>
    
        
        </form>
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Dari</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Ke</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Rate</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Tanggal</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Sumber</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    
            @forelse($rates as $row)
                <tr>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $row->from_currency }}</td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $row->to_currency }}</td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ number_format($row->rate, 4) }}</td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $row->rate_date }}</td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $row->source ?? '-' }}</td>
                    <td class="px-3 py-2 text-xs text-right whitespace-nowrap">
                        @if($can('admin', 'finance'))<a class="button mini ghost" href="{{ route('currency-rates.edit-page', $row) }}">Edit</a>@endif
                        @if($can('admin', 'finance'))<form method="post" action="{{ route('currency-rates.destroy', $row) }}">@csrf @method('delete')<button class="inline-flex items-center px-2 py-1 text-[11px] font-medium text-red-700 bg-red-50 border border-red-200 rounded hover:bg-red-100 transition">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="py-8 text-center text-slate-500">Belum ada currency rate.</td></tr>
            @endforelse
        
                </tbody>
            </table>
        </div>
    </div>

    @if($rates->hasPages())
        <div class="pagination-card flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-lg border border-[#E5E7EB] bg-white px-4 py-3 text-xs text-[#475569] shadow-xs gap-3">
            <span>Menampilkan {{$rates->firstItem()}}-{{$rates->lastItem()}} dari {{$rates->total()}}</span>
            {{$rates->links()}}
        </div>
    @endif
</div>

<x-erp.import-modal module="currency-rates" title="Impor Data Currency Rates" />
@endsection