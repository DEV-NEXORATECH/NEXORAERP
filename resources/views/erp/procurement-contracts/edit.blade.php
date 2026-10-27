@extends('layouts.erp', ['activePage' => 'procurement-contract-edit', 'pageTitle' => 'Edit Procurement Contract'])

@section('content')
<div class="max-w-4xl mx-auto space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-[#0F172A] tracking-tight">Edit Kontrak Pengadaan (Procurement Contract)</h1>
            <p class="text-xs text-[#475569]">Lengkapi formulir di bawah ini dengan data yang valid</p>
        </div>
        <a href="{{ route('procurement-contracts.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-[#475569] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F8FAFC] transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg border border-[#D1D5DB] p-6 shadow-sm">
        <form method="post" action="{{ route('procurement-contracts.update', $contract) }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2" class="space-y-4">
            
            @csrf @method('put')
            <div class="grid gap-1.5 md:col-span-2"><label>Vendor</label>
                <select name="vendor_id" class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]">
                    <option value="">Pilih vendor</option>
                    @foreach($allVendors as $vendor)<option value="{{ $vendor->id }}" @selected($contract->vendor_id === $vendor->id)>{{ $vendor->name }}</option>@endforeach
                </select>
            </div>
            <div class="grid gap-1.5"><label>Judul Kontrak</label><input name="title" value="{{ $contract->title }}" required class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]"></div>
            <div class="grid gap-1.5"><label>Nilai Kontrak</label><input name="amount" type="number" min="0" value="{{ $contract->amount }}" required class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]"></div>
            <div class="grid gap-1.5"><label>Tanggal Mulai</label><input name="start_date" type="date" value="{{ $contract->start_date?->format('Y-m-d') }}" class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]"></div>
            <div class="grid gap-1.5"><label>Tanggal Selesai</label><input name="end_date" type="date" value="{{ $contract->end_date?->format('Y-m-d') }}" class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]"></div>
            <div class="grid gap-1.5"><label>Pengingat Perpanjangan</label><input name="renewal_reminder_date" type="date" value="{{ $contract->renewal_reminder_date?->format('Y-m-d') }}" class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]"></div>
            <div class="grid gap-1.5"><label>Status</label>
                <select name="status" class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]">
                    <option value="active" @selected($contract->status === 'active')>Active</option>
                    <option value="draft" @selected($contract->status === 'draft')>Draft</option>
                    <option value="expired" @selected($contract->status === 'expired')>Expired</option>
                    <option value="terminated" @selected($contract->status === 'terminated')>Terminated</option>
                </select>
            </div>
            <div class="mt-4 flex items-center gap-3 md:col-span-2">
                <button class="btn">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Update Contract
                </button>
                <a class="button ghost" href="{{ route('procurement-contracts.index') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali
                </a>
            </div>
        
            <div class="pt-4 border-t border-[#D1D5DB] flex items-center justify-end gap-2">
                <a href="{{ route('procurement-contracts.index') }}" class="px-3.5 py-2 text-xs font-medium text-[#475569] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F8FAFC] transition">Batal</a>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition shadow-sm">Update Contract</button>
            </div>
        </form>
    </div>
</div>
@endsection
