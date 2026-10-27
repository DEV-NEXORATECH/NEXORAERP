@extends('layouts.erp', ['activePage' => 'client-contracts.create-page', 'pageTitle' => 'Tambah Kontrak Klien'])

@section('content')
<div class="erp-form-page space-y-5 max-w-4xl">
    <x-erp.page-header
        title="Catat Kontrak Klien Baru"
        subtitle="Daftarkan perjanjian kerja sama resmi, nilai kontrak, durasi efektif, dan tanggal pengingat (reminder) perpanjangan."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Sales & CRM', 'url' => route('sales-crm.index')], ['label' => 'Client Contracts', 'url' => route('client-contracts.index')], ['label' => 'Tambah Kontrak']]"
    />

    <div class="rounded-lg border border-[#D1D5DB] bg-white p-6 shadow-xs">
        <form method="post" action="{{ route('client-contracts.store') }}" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Judul Kontrak Kerja Sama <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="Contoh: Kontrak Pemeliharaan Sistem ERP Tahunan 2026-2027" required autofocus
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                    @error('title')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Klien / Mitra <span class="text-red-500">*</span>
                    </label>
                    <select name="client_id" required
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                        <option value="">-- Pilih Klien --</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" @selected(old('client_id') == $client->id)>{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Total Nilai Kontrak (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="amount" value="{{ old('amount', 0) }}" min="0" step="1" required
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Tanggal Mulai Berlaku (Start Date)
                    </label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}"
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Tanggal Berakhir (End Date)
                    </label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}"
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Tanggal Pengingat Renewal (Reminder)
                    </label>
                    <input type="date" name="reminder_date" value="{{ old('reminder_date') }}"
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Status Kontrak <span class="text-red-500">*</span>
                    </label>
                    <select name="status" required
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                        <option value="draft" @selected(old('status') === 'draft')>Draft (Penyusunan)</option>
                        <option value="active" @selected(old('status') === 'active')>Active (Berlaku)</option>
                        <option value="expired" @selected(old('status') === 'expired')>Expired (Selesai/Kedaluwarsa)</option>
                        <option value="terminated" @selected(old('status') === 'terminated')>Terminated (Dihentikan)</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Ruang Lingkup & Ketentuan Tambahan
                    </label>
                    <textarea name="notes" rows="3" placeholder="SLA layanan, klausul pembatalan, nomor dokumen fisik..."
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-4 py-2 text-xs font-semibold text-white shadow-xs hover:bg-[#001B50] transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                    Simpan Kontrak
                </button>
                <a href="{{ route('client-contracts.index') }}" class="inline-flex items-center gap-1.5 rounded-md border border-[#D1D5DB] bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-[#F3F4F6] transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
