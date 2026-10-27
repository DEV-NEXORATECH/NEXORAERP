@extends('layouts.erp', ['activePage' => 'employee-skills', 'pageTitle' => 'Tambah Skill Karyawan'])

@section('content')
<div class="space-y-5 max-w-3xl">
    <x-erp.page-header
        title="Tambah Kompetensi / Skill Karyawan"
        subtitle="Catat keahlian teknis dan fungsional karyawan untuk pemetaan proyek."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'HR & Karyawan', 'url' => route('hris.index')], ['label' => 'Skills', 'url' => route('employee-skills.index')], ['label' => 'Tambah Skill']]"
    />

    <div class="rounded-lg border border-[#D1D5DB] bg-white p-6 shadow-xs">
        <form method="post" action="{{ route('employee-skills.store') }}" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Karyawan <span class="text-red-500">*</span>
                    </label>
                    <select name="employee_id" required
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                        <option value="">-- Pilih Karyawan --</option>
                        @foreach($employees as $e)
                            <option value="{{ $e->id }}" @selected(old('employee_id') == $e->id)>{{ $e->name }}</option>
                        @endforeach
                    </select>
                    @error('employee_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Tingkat Keahlian / Level <span class="text-red-500">*</span>
                    </label>
                    <select name="level" required
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                        <option value="basic" @selected(old('level') == 'basic')>Basic (Dasar)</option>
                        <option value="intermediate" @selected(old('level') == 'intermediate')>Intermediate (Menengah)</option>
                        <option value="advanced" @selected(old('level') == 'advanced')>Advanced (Mahir)</option>
                        <option value="expert" @selected(old('level') == 'expert')>Expert (Ahli)</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nama Keahlian / Skill <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="skill" value="{{ old('skill') }}" placeholder="Contoh: Laravel, Tailwind CSS, PostgreSQL, Financial Modeling" required
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                    @error('skill')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Catatan Tambahan / Sertifikasi
                    </label>
                    <textarea name="notes" rows="3" placeholder="Informasi tambahan atau sertifikasi relevan..."
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-4 py-2 text-xs font-semibold text-white shadow-xs hover:bg-[#001B50] transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                    Simpan Skill
                </button>
                <a href="{{ route('employee-skills.index') }}" class="inline-flex items-center gap-1.5 rounded-md border border-[#D1D5DB] bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-[#F3F4F6] transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
