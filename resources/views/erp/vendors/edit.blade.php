@extends('layouts.erp', ['activePage' => 'vendors', 'pageTitle' => 'Edit Vendor'])

@section('content')
<div class="erp-form-page space-y-5 max-w-3xl">
    <x-erp.page-header
        title="Edit Profil Vendor / Supplier"
        subtitle="Perbarui data kontak, kategori pasokan, dan syarat pembayaran untuk: {{ $vendor->name }}"
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Procurement', 'url' => route('procurement.index')], ['label' => 'Vendors', 'url' => route('vendors.index')], ['label' => 'Edit']]"
    />

    <div class="rounded-lg border border-[#D1D5DB] bg-white p-6 shadow-xs">
        <form method="post" action="{{ route('vendors.update', $vendor) }}" class="space-y-5">
            @csrf
            @method('put')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nama Vendor / Badan Usaha Supplier <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $vendor->name) }}" required autofocus
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Kategori Pasokan
                    </label>
                    <input type="text" name="category" value="{{ old('category', $vendor->category) }}"
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nama Kontak / PIC Vendor
                    </label>
                    <input type="text" name="contact_name" value="{{ old('contact_name', $vendor->contact_name) }}"
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Alamat Email Resmi
                    </label>
                    <input type="email" name="email" value="{{ old('email', $vendor->email) }}"
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nomor Telepon / Hotline
                    </label>
                    <input type="text" name="phone" value="{{ old('phone', $vendor->phone) }}"
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Termin Pembayaran (Payment Terms)
                    </label>
                    <input type="text" name="payment_terms" value="{{ old('payment_terms', $vendor->payment_terms) }}"
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Status Kemitraan <span class="text-red-500">*</span>
                    </label>
                    <select name="status" required
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                        <option value="active" @selected($vendor->status === 'active')>Active (Pemasok Aktif)</option>
                        <option value="inactive" @selected($vendor->status === 'inactive')>Inactive (Tidak Aktif)</option>
                        <option value="blacklisted" @selected($vendor->status === 'blacklisted')>Blacklisted (Diblokir)</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-4 py-2 text-xs font-semibold text-white shadow-xs hover:bg-[#001B50] transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                    Perbarui Vendor
                </button>
                <a href="{{ route('vendors.index') }}" class="inline-flex items-center gap-1.5 rounded-md border border-[#D1D5DB] bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-[#F3F4F6] transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
