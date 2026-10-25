@extends('layouts.erp', ['activePage' => 'chart-accounts', 'pageTitle' => 'Chart of Accounts'])

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Bagan Akun (Chart of Accounts)</h1>
            <p class="text-xs text-gray-500">Kelola master data dan transaksi Bagan Akun (Chart of Accounts)</p>
        </div>
        <x-erp.toolbar-actions module="chart-accounts" />
    </div>

    <!-- Table Card -->
    
    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-card">
        <form method="get" action="{{ route('chart-accounts.index') }}" class="filter-toolbar">
            
            
        <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="filter-search !w-full pl-8 pr-3 text-xs">
        <select name="type" class="px-2.5 py-1.5 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white">
            <option value="">Semua Tipe</option>
            <option value="asset" @selected(request('type') == 'asset')>Asset</option>
            <option value="liability" @selected(request('type') == 'liability')>Liability</option>
            <option value="equity" @selected(request('type') == 'equity')>Equity</option>
            <option value="income" @selected(request('type') == 'income')>Income</option>
            <option value="expense" @selected(request('type') == 'expense')>Expense</option>
        </select>
        <select name="is_active" class="px-2.5 py-1.5 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white">
            <option value="">Semua Status</option>
            <option value="1" @selected(request('is_active') == '1')>Active</option>
            <option value="0" @selected(request('is_active') == '0')>Inactive</option>
        </select>
        <div class="flex gap-2 items-end">
            <button class="px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition">Filter</button>
            <a href="{{ route('chart-accounts.index') }}" class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Reset</a>
        </div>
    
        
        </form>
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Code</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Name</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Type</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Status</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    
            @forelse($accounts as $account)
                <tr>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $account->code }}</td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $account->name }}<br><span class="muted">{{ $account->parent?->code ? $account->parent->code.' - '.$account->parent->name : 'Root' }}</span></td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left"><span class="badge">{{ $account->type }}</span></td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left"><span class="badge badge-{{ $account->is_active ? 'active' : 'void' }}">{{ $account->is_active ? 'active' : 'inactive' }}</span></td>
                    <td class="px-3 py-2 text-xs text-right whitespace-nowrap">
                        @if($can('admin', 'finance'))<a class="button mini ghost" href="{{ route('chart-accounts.edit-page', $account) }}">Edit</a>@endif
                        @if($can('admin'))<form method="post" action="{{ route('chart-accounts.destroy', $account) }}">@csrf @method('delete')<button class="inline-flex items-center px-2 py-1 text-[11px] font-medium text-red-700 bg-red-50 border border-red-200 rounded hover:bg-red-100 transition">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="py-8 text-center text-slate-500">Belum ada CoA.</td></tr>
            @endforelse
        
                </tbody>
            </table>
        </div>
    </div>

    @if($accounts->hasPages())
        <div class="pagination-card flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-lg border border-[#E5E7EB] bg-white px-4 py-3 text-xs text-[#475569] shadow-xs gap-3">
            <span>Menampilkan {{$accounts->firstItem()}}-{{$accounts->lastItem()}} dari {{$accounts->total()}}</span>
            {{$accounts->links()}}
        </div>
    @endif
</div>

<x-erp.import-modal module="chart-accounts" title="Impor Data Chart Accounts" />
@endsection