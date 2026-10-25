@extends('layouts.erp', ['activePage' => 'purchase-matches.index', 'pageTitle' => 'Purchase Matches'])

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Pencocokan Faktur (3-Way Matching)</h1>
            <p class="text-xs text-gray-500">Kelola master data dan transaksi Pencocokan Faktur (3-Way Matching)</p>
        </div>
        <x-erp.toolbar-actions module="purchase-matches" :create-route="route('purchase-matches.create-page')" create-label="+ Buat Pencocokan" />
    </div>

    <!-- Table Card -->
    
    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-card">
        <form method="get" action="{{ route('purchase-matches.index') }}" class="filter-toolbar">
            
            
        <select name="status" class="px-2.5 py-1.5 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white">
            <option value="">Semua Status</option>
            <option value="matched" @selected(request('status') == 'matched')>Matched</option>
            <option value="unmatched" @selected(request('status') == 'unmatched')>Unmatched</option>
            <option value="partial" @selected(request('status') == 'partial')>Partial</option>
        </select>
        <div class="flex gap-2 items-end">
            <button class="px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition">Filter</button>
            <a href="{{ route('purchase-matches.index') }}" class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Reset</a>
        </div>
    
        
        </form>
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">PO</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Goods Receipt</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Variance</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Status</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Matched At</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    
            @forelse($matches as $row)
                <tr>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $row->purchaseOrder?->number ?? '#' . $row->purchase_order_id ?? '-' }}</td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">#{{ $row->goodsReceipt?->id ?? $row->goods_receipt_id ?? '-' }}</td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $row->variance_amount !== null ? $rp($row->variance_amount) : '-' }}</td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left"><span class="badge badge-{{ $row->match_status === 'matched' ? 'active' : ($row->match_status === 'partial' ? 'pending' : 'void') }}">{{ $row->match_status }}</span></td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $row->matched_at ?? '-' }}</td>
                    <td class="px-3 py-2 text-xs text-right whitespace-nowrap">
                        @if($can('admin', 'finance'))<a class="button mini ghost" href="{{ route('purchase-matches.edit-page', $row) }}">Edit</a>@endif
                        @if($can('admin', 'finance'))<form method="post" action="{{ route('purchase-matches.destroy', $row) }}">@csrf @method('delete')<button class="inline-flex items-center px-2 py-1 text-[11px] font-medium text-red-700 bg-red-50 border border-red-200 rounded hover:bg-red-100 transition">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="py-8 text-center text-slate-500">Belum ada purchase match.</td></tr>
            @endforelse
        
                </tbody>
            </table>
        </div>
    </div>

    @if($matches->hasPages())
        <div class="pagination-card flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-lg border border-[#E5E7EB] bg-white px-4 py-3 text-xs text-[#475569] shadow-xs gap-3">
            <span>Menampilkan {{$matches->firstItem()}}-{{$matches->lastItem()}} dari {{$matches->total()}}</span>
            {{$matches->links()}}
        </div>
    @endif
</div>

<x-erp.import-modal module="purchase-matches" title="Impor Data Purchase Matches" />
@endsection