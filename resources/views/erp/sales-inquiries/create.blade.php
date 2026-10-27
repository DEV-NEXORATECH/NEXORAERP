@extends('layouts.erp', ['activePage' => 'sales-inquiries.create-page', 'pageTitle' => 'Tambah Inquiry'])

@section('content')
<div class="space-y-5 max-w-4xl">
    <x-erp.page-header
        title="Catat Sales Inquiry Baru"
        subtitle="Registrasikan permintaan informasi produk, konsultasi awal, atau kebutuhan prospek bisnis."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Sales & CRM', 'url' => route('sales-crm.index')], ['label' => 'Sales Inquiries', 'url' => route('sales-inquiries.index')], ['label' => 'Tambah Inquiry']]"
    />

    <div class="rounded-lg border border-[#D1D5DB] bg-white p-6 shadow-xs">
        <form method="post" action="{{ route('sales-inquiries.store') }}" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nama Perusahaan / Organisasi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="company_name" value="{{ old('company_name') }}" placeholder="PT Contoh Prospek Gemilang" required autofocus
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                    @error('company_name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nama Kontak / PIC
                    </label>
                    <input type="text" name="contact_name" value="{{ old('contact_name') }}" placeholder="Bpk. Hendra"
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Email Kontak
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="hendra@prospek.co.id"
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nomor Telepon / WhatsApp
                    </label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="08123456789"
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Sumber Prospek (Lead Source)
                    </label>
                    <input type="text" name="source" value="{{ old('source') }}" placeholder="Website, Referral, Event, Cold Outreach"
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Ringkasan Kebutuhan
                    </label>
                    <input type="text" name="need" value="{{ old('need') }}" placeholder="ERP System Implementation, Cloud Migration"
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Status Inquiry
                    </label>
                    <select name="status"
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                        <option value="new" @selected(old('status') === 'new')>New (Baru Masuk)</option>
                        <option value="contacted" @selected(old('status') === 'contacted')>Contacted (Sudah Dihubungi)</option>
                        <option value="qualified" @selected(old('status') === 'qualified')>Qualified (Prospek Layak)</option>
                        <option value="lost" @selected(old('status') === 'lost')>Lost (Gugur)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        PIC Sales / Penanggung Jawab
                    </label>
                    <select name="owner_id"
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                        <option value="">-- Pilih Sales Representative --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected(old('owner_id') == $user->id)>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Catatan Diskusi / Riwayat Interaksi
                    </label>
                    <textarea name="notes" rows="3" placeholder="Rangkuman hasil telepon, requirement client, atau target timeline..."
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-4 py-2 text-xs font-semibold text-white shadow-xs hover:bg-[#001B50] transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                    Simpan Inquiry
                </button>
                <a href="{{ route('sales-inquiries.index') }}" class="inline-flex items-center gap-1.5 rounded-md border border-[#D1D5DB] bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-[#F3F4F6] transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
