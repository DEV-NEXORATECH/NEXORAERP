@extends('layouts.erp', ['activePage' => 'procurement-contracts', 'pageTitle' => 'Procurement Contract'])

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Kontrak Pengadaan (Procurement Contract)</h1>
            <p class="text-xs text-gray-500">Kelola master data dan transaksi Kontrak Pengadaan (Procurement Contract)</p>
        </div>
        <a href="{{ route('procurement-contracts.create-page') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Kontrak
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
        <form method="get" action="{{ route('procurement-contracts.index') }}" class="p-3 border-b border-gray-200 bg-gray-50/50 flex flex-wrap items-center gap-2.5">
            
        <input type="text" name="search" placeholder="Cari Contract..." value="{{ request('search') }}" class="px-2.5 py-1.5 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white">
        @if(isset($vendors))
        <select name="vendor_id" class="px-2.5 py-1.5 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white">
            <option value="">Semua Vendor</option>
            @foreach($vendors as $vendor)
            <option value="{{ $vendor->id }}" @selected(request('vendor_id') == $vendor->id)>{{ $vendor->name }}</option>
            @endforeach
        </select>
        @endif
        <select name="status" class="px-2.5 py-1.5 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white">
            <option value="">Semua Status</option>
            <option value="draft" @selected(request('status') == 'draft')>Draft</option>
            <option value="active" @selected(request('status') == 'active')>Active</option>
            <option value="expired" @selected(request('status') == 'expired')>Expired</option>
            <option value="terminated" @selected(request('status') == 'terminated')>Terminated</option>
        </select>
        <div class="flex gap-2 items-end">
            <button class="px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition">Filter</button>
            <a href="{{ route('procurement-contracts.index') }}" class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Reset</a>
        </div>
    
        </form>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Contract</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Vendor</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Amount</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Status</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    
            @forelse($contracts as $row)
                <tr>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $row->contract_number }}<br><span class="muted">{{ $row->title }}</span></td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $row->vendor->name ?? '-' }}</td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $rp($row->amount) }}</td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left"><span class="badge badge-{{ $row->status === 'active' ? 'active' : 'pending' }}">{{ $row->status }}</span></td>
                    <td class="px-3 py-2 text-xs text-right whitespace-nowrap">
                        <a class="button mini ghost" href="{{ route('procurement-contracts.edit-page', $row) }}">Edit</a>
                        <form method="post" action="{{ route('procurement-contracts.destroy', $row) }}">@csrf @method('delete')<button class="inline-flex items-center px-2 py-1 text-[11px] font-medium text-red-700 bg-red-50 border border-red-200 rounded hover:bg-red-100 transition">Hapus</button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="py-8 text-center text-slate-500">Belum ada contract.</td></tr>
            @endforelse
        
                </tbody>
            </table>
        </div>
        @if($contracts->hasPages())
        <div class="p-3 border-t border-gray-200 bg-gray-50/50 flex items-center justify-between text-xs text-gray-500">
            <span>Menampilkan {{$contracts->firstItem()}}-{{$contracts->lastItem()}} dari {{$contracts->total()}}</span>
            {{$contracts->links()}}
        </div>
        @endif
    </div>
</div>
@endsection
