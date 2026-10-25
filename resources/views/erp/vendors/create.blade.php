@extends('layouts.erp', ['activePage' => 'vendors', 'pageTitle' => 'Tambah Vendor Baru'])

@section('content')
<div class="space-y-5 max-w-3xl">
    <x-erp.page-header
        title="Daftarkan Vendor / Supplier Baru"
        subtitle="Registrasikan profil pemasok barang, lisensi software, atau penyedia jasa untuk pengadaan barang (procurement)."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Procurement', 'url' => route('procurement.index')], ['label' => 'Vendors', 'url' => route('vendors.index')], ['label' => 'Tambah Vendor']]"
    />

    <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-xs">
        <form method="post" action="{{ route('vendors.store') }}" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nama Vendor / Badan Usaha Supplier <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: PT Mitra Solusi Cloud Nusantara" required autofocus
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Kategori Pasokan
                    </label>
                    <input type="text" name="category" value="{{ old('category') }}" placeholder="Software, Cloud Hosting, Hardware, Jasa Konsultan"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nama Kontak / PIC Vendor
                    </label>
                    <input type="text" name="contact_name" value="{{ old('contact_name') }}" placeholder="Ibu Rina"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Alamat Email Resmi
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="sales@mitrasolusi.co.id"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nomor Telepon / Hotline
                    </label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="021-5550199"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Termin Pembayaran (Payment Terms)
                    </label>
                    <input type="text" name="payment_terms" value="{{ old('payment_terms', 'Net 30') }}" placeholder="Net 30, Net 14, COD"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Status Kemitraan <span class="text-red-500">*</span>
                    </label>
                    <select name="status" required
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                        <option value="active" @selected(old('status') === 'active')>Active (Pemasok Aktif)</option>
                        <option value="inactive" @selected(old('status') === 'inactive')>Inactive (Tidak Aktif)</option>
                        <option value="blacklisted" @selected(old('status') === 'blacklisted')>Blacklisted (Diblokir)</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-4 py-2 text-xs font-semibold text-white shadow-xs hover:bg-[#001B50] transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                    Simpan Vendor
                </button>
                <a href="{{ route('vendors.index') }}" class="inline-flex items-center gap-1.5 rounded-md border border-slate-300 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
