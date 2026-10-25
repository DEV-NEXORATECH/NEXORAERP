@extends('layouts.erp', ['activePage' => 'bank-reconciliation-items.index', 'pageTitle' => 'Bank Reconciliation'])

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Rekonsiliasi Bank (Bank Reconciliation)</h1>
            <p class="text-xs text-gray-500">Kelola master data dan transaksi Rekonsiliasi Bank (Bank Reconciliation)</p>
        </div>
        <x-erp.toolbar-actions module="bank-reconciliation-items" :create-route="route('bank-reconciliation-items.create-page')" create-label="+ Tambah Item Rekonsiliasi" />
    </div>

    <!-- Table Card -->
    
    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-card">
        <form method="get" action="{{ route('bank-reconciliation-items.index') }}" class="filter-toolbar">
            
            
        @if(isset($banks))
        <select name="bank_account_id" class="px-2.5 py-1.5 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white">
            <option value="">Semua Bank</option>
            @foreach($banks as $bank)
            <option value="{{ $bank->id }}" @selected(request('bank_account_id') == $bank->id)>{{ $bank->name }}</option>
            @endforeach
        </select>
        @endif
        <select name="reconciled" class="px-2.5 py-1.5 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white">
            <option value="">Semua Status</option>
            <option value="1" @selected(request('reconciled') == '1')>Reconciled</option>
            <option value="0" @selected(request('reconciled') == '0')>Unreconciled</option>
        </select>
        <div class="flex gap-2 items-end">
            <button class="px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition">Filter</button>
            <a href="{{ route('bank-reconciliation-items.index') }}" class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Reset</a>
        </div>
    
        
        </form>
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Bank</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Tanggal</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Deskripsi</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Amount</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Tipe</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Reconciled</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    
            @forelse($reconciliations as $row)
                <tr>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $row->bankAccount?->name ?? '-' }}</td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $row->statement_date }}</td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $row->description ?? $row->reference ?? '-' }}<br><span class="muted">{{ $row->reference ?? '' }}</span></td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $rp($row->amount) }}</td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left"><span class="badge badge-{{ $row->type === 'credit' ? 'active' : 'pending' }}">{{ $row->type }}</span></td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left"><span class="badge badge-{{ $row->reconciled ? 'active' : 'void' }}">{{ $row->reconciled ? 'Yes' : 'No' }}</span></td>
                    <td class="px-3 py-2 text-xs text-right whitespace-nowrap">
                        @if($can('admin', 'finance'))<a class="button mini ghost" href="{{ route('bank-reconciliation-items.edit-page', $row) }}">Edit</a>@endif
                        @if($can('admin', 'finance'))<form method="post" action="{{ route('bank-reconciliation-items.destroy', $row) }}">@csrf @method('delete')<button class="inline-flex items-center px-2 py-1 text-[11px] font-medium text-red-700 bg-red-50 border border-red-200 rounded hover:bg-red-100 transition">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="py-8 text-center text-slate-500">Belum ada reconciliation item.</td></tr>
            @endforelse
        
                </tbody>
            </table>
        </div>
    </div>

    @if($reconciliations->hasPages())
        <div class="pagination-card flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-lg border border-[#E5E7EB] bg-white px-4 py-3 text-xs text-[#475569] shadow-xs gap-3">
            <span>Menampilkan {{$reconciliations->firstItem()}}-{{$reconciliations->lastItem()}} dari {{$reconciliations->total()}}</span>
            {{$reconciliations->links()}}
        </div>
    @endif
</div>

<x-erp.import-modal module="bank-reconciliation-items" title="Impor Data Bank Reconciliation Items" />
@endsection