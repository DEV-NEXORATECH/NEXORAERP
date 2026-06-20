@extends('layouts.erp', ['activePage' => 'procurement-contract-edit', 'pageTitle' => 'Edit Procurement Contract'])

@section('content')
<section class="section">
    <div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
        <div class="mb-8 border-b border-[#d7e3ef] pb-5">
            <h2 class="mb-1">Edit Procurement Contract</h2>
            <p class="muted">Perbarui data kontrak procurement.</p>
        </div>
        <form method="post" action="{{ route('procurement-contracts.update', $contract) }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
            @csrf @method('put')
            <div class="grid gap-1.5 md:col-span-2"><label>Vendor</label>
                <select name="vendor_id">
                    <option value="">Pilih vendor</option>
                    @foreach($allVendors as $vendor)<option value="{{ $vendor->id }}" @selected($contract->vendor_id === $vendor->id)>{{ $vendor->name }}</option>@endforeach
                </select>
            </div>
            <div class="grid gap-1.5"><label>Judul Kontrak</label><input name="title" value="{{ $contract->title }}" required></div>
            <div class="grid gap-1.5"><label>Nilai Kontrak</label><input name="amount" type="number" min="0" value="{{ $contract->amount }}" required></div>
            <div class="grid gap-1.5"><label>Tanggal Mulai</label><input name="start_date" type="date" value="{{ $contract->start_date?->format('Y-m-d') }}"></div>
            <div class="grid gap-1.5"><label>Tanggal Selesai</label><input name="end_date" type="date" value="{{ $contract->end_date?->format('Y-m-d') }}"></div>
            <div class="grid gap-1.5"><label>Pengingat Perpanjangan</label><input name="renewal_reminder_date" type="date" value="{{ $contract->renewal_reminder_date?->format('Y-m-d') }}"></div>
            <div class="grid gap-1.5"><label>Status</label>
                <select name="status">
                    <option value="active" @selected($contract->status === 'active')>Active</option>
                    <option value="draft" @selected($contract->status === 'draft')>Draft</option>
                    <option value="expired" @selected($contract->status === 'expired')>Expired</option>
                    <option value="terminated" @selected($contract->status === 'terminated')>Terminated</option>
                </select>
            </div>
            <div class="mt-4 flex items-center gap-3 md:col-span-2">
                <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Update Contract
                </button>
                <a class="button ghost" href="{{ route('procurement-contracts.index') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali
                </a>
            </div>
        </form>
    </div>
</section>
@endsection
