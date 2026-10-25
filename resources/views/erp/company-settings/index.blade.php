@extends('layouts.erp', ['activePage' => 'company-settings', 'pageTitle' => 'Company Settings'])

@section('content')
<div class="space-y-5 max-w-4xl">
    <x-erp.page-header
        title="Pengaturan Identitas Perusahaan"
        subtitle="Kelola identitas resmi, NPWP, kontak legal, rekening default, dan logo untuk invoice, proposal, dan cetak PDF."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Administration', 'url' => route('admin.index')], ['label' => 'Company Settings']]"
    >
        <x-slot:actions>
            <x-erp.toolbar-actions module="company_settings" :can-create="false" />
        </x-slot:actions>
    </x-erp.page-header>

    <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-xs">
        <form method="post" action="{{ route('company-settings.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('put')

            {{-- 1. Identitas Utama --}}
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#002875] border-b border-slate-200 pb-2 mb-4">
                    1. Identitas Badan Usaha
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nama Resmi Perusahaan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="company_name" value="{{ old('company_name', $companySetting->company_name) }}" required
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                        @error('company_name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nomor Pokok Wajib Pajak (NPWP)
                        </label>
                        <input type="text" name="npwp" value="{{ old('npwp', $companySetting->npwp) }}" placeholder="00.000.000.0-000.000"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nama Pejabat Penandatangan Dokumen
                        </label>
                        <input type="text" name="signature_name" value="{{ old('signature_name', $companySetting->signature_name) }}" placeholder="Direktur Utama / Finance Head"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                    </div>
                </div>
            </div>

            {{-- 2. Kontak & Alamat --}}
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#002875] border-b border-slate-200 pb-2 mb-4">
                    2. Kontak & Alamat Kantor
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Email Korespondensi
                        </label>
                        <input type="email" name="email" value="{{ old('email', $companySetting->email) }}" placeholder="finance@nexora.co.id"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nomor Telepon Kantor
                        </label>
                        <input type="text" name="phone" value="{{ old('phone', $companySetting->phone) }}" placeholder="+62 21 555 1234"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Alamat Lengkap Kantor
                        </label>
                        <textarea name="address" rows="3" placeholder="Gedung, Jalan, Kota, Kode Pos..."
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">{{ old('address', $companySetting->address) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- 3. Default Finansial & Logo --}}
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#002875] border-b border-slate-200 pb-2 mb-4">
                    3. Rekening Default & Logo Brand
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Rekening Bank Default (Untuk Invoice / Tagihan)
                        </label>
                        <select name="default_bank_account_id"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                            <option value="">-- Pilih Rekening Default --</option>
                            @foreach($bankAccounts as $bank)
                                <option value="{{ $bank->id }}" @selected($companySetting->default_bank_account_id === $bank->id)>
                                    {{ $bank->name }} ({{ $bank->bank_name }} - {{ $bank->account_number }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Unggah Logo Baru
                        </label>
                        <input type="file" name="logo" accept="image/png,image/jpeg,image/svg+xml"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[11px] file:bg-slate-100 file:font-semibold">
                        <span class="text-[11px] text-slate-500 mt-1 block">Format PNG, JPG, atau SVG (Maks. 2MB).</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-200">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-5 py-2.5 text-xs font-semibold text-white shadow-xs hover:bg-[#001B50] focus:outline-none focus:ring-2 focus:ring-[#002875]/20 transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                    Simpan Perubahan Pengaturan
                </button>
                <a href="{{ route('admin.index') }}" class="inline-flex items-center gap-1.5 rounded-md border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Kembali ke Admin
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
