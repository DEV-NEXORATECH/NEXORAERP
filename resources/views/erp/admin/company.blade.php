@extends('layouts.erp', ['activePage' => 'company', 'pageTitle' => 'Company Setting'])

@section('content')
<div class="space-y-6 max-w-4xl">
    {{-- Header --}}
    <div class="flex flex-col gap-4 rounded-[12px] border border-[#D9E2EC] bg-white p-5 shadow-xs md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3.5">
            <a href="{{ route('admin.index') }}" class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-[8px] border border-[#D9E2EC] bg-white text-[#475569] transition hover:border-[#0B2345] hover:text-[#0B2345]" title="Kembali ke Admin">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-[#0B2345]">Organization Profile</span>
                    <span class="text-[#475569]">·</span>
                    <span class="text-[11px] font-medium text-[#475569]">General Settings</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#0F172A]">Pengaturan Perusahaan</h1>
                <p class="mt-0.5 text-xs text-[#475569]">Konfigurasi identitas resmi, alamat, legalitas pajak (NPWP), dan rekening bank default untuk faktur/invoice.</p>
            </div>
        </div>
    </div>

    {{-- Setting Form --}}
    <div class="rounded-[12px] border border-[#D9E2EC] bg-white p-6 shadow-xs">
        <form method="post" action="{{ route('company-settings.update') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('put')

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-xs font-semibold text-[#0F172A]">Nama Perusahaan <span class="text-[#DC2626]">*</span></label>
                    <input name="company_name" value="{{ $companySetting->company_name }}" required placeholder="PT Contoh Solusi Indonesia" class="field-input w-full">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold text-[#0F172A]">Email Resmi Perusahaan</label>
                    <input name="email" type="email" value="{{ $companySetting->email }}" placeholder="contact@company.com" class="field-input w-full">
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-xs font-semibold text-[#0F172A]">Nomor Telepon / WhatsApp</label>
                    <input name="phone" value="{{ $companySetting->phone }}" placeholder="+62 21 555 1234" class="field-input w-full">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold text-[#0F172A]">Nomor Pokok Wajib Pajak (NPWP)</label>
                    <input name="npwp" value="{{ $companySetting->npwp }}" placeholder="01.234.567.8-901.000" class="field-input w-full">
                </div>
            </div>

            <div>
                <label class="mb-1 block text-xs font-semibold text-[#0F172A]">Alamat Lengkap Kantor</label>
                <textarea name="address" rows="3" placeholder="Alamat gedung, jalan, kota, kode pos" class="field-input w-full">{{ $companySetting->address }}</textarea>
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-xs font-semibold text-[#0F172A]">Nama Penandatangan Dokumen / Direktur</label>
                    <input name="signature_name" value="{{ $companySetting->signature_name }}" placeholder="Nama Direktur / Authorized Signer" class="field-input w-full">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold text-[#0F172A]">Rekening Bank Utama (Default Invoice)</label>
                    <select name="default_bank_account_id" class="field-input w-full">
                        <option value="">Pilih Bank Default</option>
                        @foreach($bankAccounts as $bank)
                            <option value="{{ $bank->id }}" @selected($companySetting->default_bank_account_id===$bank->id)>{{ $bank->name }} ({{ $bank->bank_name }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="border-t border-[#D9E2EC] pt-4">
                <label class="mb-1 block text-xs font-semibold text-[#0F172A]">Logo Perusahaan (Untuk Invoice & Dokumen)</label>
                <input name="logo" type="file" class="field-input w-full file:mr-4 file:py-1 file:px-3 file:rounded-[6px] file:border-0 file:text-xs file:font-semibold file:bg-[#E8EEF5] file:text-[#0B2345] hover:file:bg-[#D9E2EC]">
                <p class="mt-1 text-[11px] text-[#475569]">Format didukung: PNG, JPG, SVG (Maks. 2MB). Disarankan berlatar transparan.</p>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-[#D9E2EC] pt-4">
                <a href="{{ route('admin.index') }}" class="btn btn-outline h-9 px-4 text-xs font-semibold">Batal</a>
                <button type="submit" class="btn btn-primary h-9 px-5 text-xs font-semibold">Simpan Pengaturan</button>
            </div>
        </form>
    </div>
</div>
@endsection
