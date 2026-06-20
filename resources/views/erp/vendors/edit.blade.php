@extends('layouts.erp', ['activePage' => 'vendor-edit', 'pageTitle' => 'Edit Vendor'])

@section('content')
<section class="section">
    <div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
        <div class="mb-8 border-b border-[#d7e3ef] pb-5">
            <h2 class="mb-1">Edit Vendor</h2>
            <p class="muted">Perbarui data supplier / vendor.</p>
        </div>
        <form method="post" action="{{ route('vendors.update', $vendor) }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
            @csrf @method('put')
            <div class="grid gap-1.5"><label>Nama Vendor</label><input name="name" value="{{ $vendor->name }}" required></div>
            <div class="grid gap-1.5"><label>Kategori</label><input name="category" value="{{ $vendor->category }}" placeholder="Software / cloud / hardware"></div>
            <div class="grid gap-1.5"><label>PIC</label><input name="contact_name" value="{{ $vendor->contact_name }}"></div>
            <div class="grid gap-1.5"><label>Email</label><input name="email" type="email" value="{{ $vendor->email }}"></div>
            <div class="grid gap-1.5"><label>Telepon</label><input name="phone" value="{{ $vendor->phone }}"></div>
            <div class="grid gap-1.5"><label>Payment Terms</label><input name="payment_terms" value="{{ $vendor->payment_terms }}" placeholder="Net 30"></div>
            <div class="grid gap-1.5"><label>Status</label>
                <select name="status">
                    <option value="active" @selected($vendor->status === 'active')>Active</option>
                    <option value="inactive" @selected($vendor->status === 'inactive')>Inactive</option>
                    <option value="blacklisted" @selected($vendor->status === 'blacklisted')>Blacklisted</option>
                </select>
            </div>
            <div class="mt-4 flex items-center gap-3 md:col-span-2">
                <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Update Vendor
                </button>
                <a class="button ghost" href="{{ route('vendors.index') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali
                </a>
            </div>
        </form>
    </div>
</section>
@endsection
