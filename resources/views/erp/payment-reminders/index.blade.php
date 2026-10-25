@extends('layouts.erp', ['activePage' => 'payment-reminders', 'pageTitle' => 'Payment Reminders'])

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Pengingat Pembayaran (Payment Reminders)</h1>
            <p class="text-xs text-gray-500">Kelola master data dan transaksi Pengingat Pembayaran (Payment Reminders)</p>
        </div>
        <x-erp.toolbar-actions module="payment-reminders" />
    </div>

    <!-- Table Card -->
    
    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-card">
        <form method="get" action="{{ route('payment-reminders.index') }}" class="filter-toolbar">
            
            
        <select name="status" class="px-2.5 py-1.5 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white">
            <option value="">Semua Status</option>
            <option value="scheduled" @selected(request('status') == 'scheduled')>Scheduled</option>
            <option value="sent" @selected(request('status') == 'sent')>Sent</option>
            <option value="cancelled" @selected(request('status') == 'cancelled')>Cancelled</option>
        </select>
        <select name="channel" class="px-2.5 py-1.5 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white">
            <option value="">Semua Channel</option>
            <option value="email" @selected(request('channel') == 'email')>Email</option>
            <option value="whatsapp" @selected(request('channel') == 'whatsapp')>WhatsApp</option>
            <option value="phone" @selected(request('channel') == 'phone')>Phone</option>
        </select>
        <div class="flex gap-2 items-end">
            <button class="px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition">Filter</button>
            <a href="{{ route('payment-reminders.index') }}" class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Reset</a>
        </div>
    
        
        </form>
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Invoice</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Reminder Date</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Channel</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Status</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    
            @forelse($reminders as $row)
                <tr>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $row->invoice?->number ?? '-' }}</td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $row->reminder_date }}</td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $row->channel }}</td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left"><span class="badge badge-{{ $row->status === 'scheduled' ? 'pending' : ($row->status === 'sent' ? 'active' : 'void') }}">{{ $row->status }}</span></td>
                    <td class="px-3 py-2 text-xs text-right whitespace-nowrap">
                        @if($can('admin', 'finance'))<a class="button mini ghost" href="{{ route('payment-reminders.edit-page', $row) }}">Edit</a>@endif
                        @if($can('admin'))<form method="post" action="{{ route('payment-reminders.destroy', $row) }}">@csrf @method('delete')<button class="inline-flex items-center px-2 py-1 text-[11px] font-medium text-red-700 bg-red-50 border border-red-200 rounded hover:bg-red-100 transition">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="py-8 text-center text-slate-500">Belum ada payment reminder.</td></tr>
            @endforelse
        
                </tbody>
            </table>
        </div>
    </div>

    @if($reminders->hasPages())
        <div class="pagination-card flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-lg border border-[#E5E7EB] bg-white px-4 py-3 text-xs text-[#475569] shadow-xs gap-3">
            <span>Menampilkan {{$reminders->firstItem()}}-{{$reminders->lastItem()}} dari {{$reminders->total()}}</span>
            {{$reminders->links()}}
        </div>
    @endif
</div>

<x-erp.import-modal module="payment-reminders" title="Impor Data Payment Reminders" />
@endsection