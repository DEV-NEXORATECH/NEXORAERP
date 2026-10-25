@extends('layouts.erp', ['activePage' => 'tax-rules', 'pageTitle' => 'Tax Rules'])

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Aturan Pajak (Tax Rules)</h1>
            <p class="text-xs text-gray-500">Kelola master data dan transaksi Aturan Pajak (Tax Rules)</p>
        </div>
        <a href="{{ route('tax-rules.create-page') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Pajak
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
        <form method="get" action="{{ route('tax-rules.index') }}" class="p-3 border-b border-gray-200 bg-gray-50/50 flex flex-wrap items-center gap-2.5">
            
        <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="px-2.5 py-1.5 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white">
        <select name="tax_type" class="px-2.5 py-1.5 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white">
            <option value="">Semua Tipe</option>
            <option value="PPN" @selected(request('tax_type') == 'PPN')>PPN</option>
            <option value="PPh 21" @selected(request('tax_type') == 'PPh 21')>PPh 21</option>
            <option value="PPh 23" @selected(request('tax_type') == 'PPh 23')>PPh 23</option>
            <option value="PPh 4(2)" @selected(request('tax_type') == 'PPh 4(2)')>PPh 4(2)</option>
        </select>
        <select name="is_active" class="px-2.5 py-1.5 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white">
            <option value="">Semua Status</option>
            <option value="1" @selected(request('is_active') == '1')>Active</option>
            <option value="0" @selected(request('is_active') == '0')>Inactive</option>
        </select>
        <div class="flex gap-2 items-end">
            <button class="px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition">Filter</button>
            <a href="{{ route('tax-rules.index') }}" class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Reset</a>
        </div>
    
        </form>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Rule</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Tax Type</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Rate</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Direction</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Status</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    
            @forelse($taxRules as $tax)
                <tr>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $tax->name }}</td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left"><span class="badge">{{ $tax->tax_type }}</span></td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $tax->rate }}%</td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $tax->direction }}</td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left"><span class="badge badge-{{ $tax->is_active ? 'active' : 'void' }}">{{ $tax->is_active ? 'active' : 'inactive' }}</span></td>
                    <td class="px-3 py-2 text-xs text-right whitespace-nowrap">
                        @if($can('admin', 'finance'))<a class="button mini ghost" href="{{ route('tax-rules.edit-page', $tax) }}">Edit</a>@endif
                        @if($can('admin'))<form method="post" action="{{ route('tax-rules.destroy', $tax) }}">@csrf @method('delete')<button class="inline-flex items-center px-2 py-1 text-[11px] font-medium text-red-700 bg-red-50 border border-red-200 rounded hover:bg-red-100 transition">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="py-8 text-center text-slate-500">Belum ada tax rule.</td></tr>
            @endforelse
        
                </tbody>
            </table>
        </div>
        @if($taxRules->hasPages())
        <div class="p-3 border-t border-gray-200 bg-gray-50/50 flex items-center justify-between text-xs text-gray-500">
            <span>Menampilkan {{$taxRules->firstItem()}}-{{$taxRules->lastItem()}} dari {{$taxRules->total()}}</span>
            {{$taxRules->links()}}
        </div>
        @endif
    </div>
</div>
@endsection
