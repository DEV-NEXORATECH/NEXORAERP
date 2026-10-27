@extends('layouts.erp', ['activePage' => 'employee-skills', 'pageTitle' => 'Edit Skill Karyawan'])

@section('content')
<div class="erp-form-page space-y-5 max-w-3xl">
    <x-erp.page-header
        title="Edit Kompetensi / Skill Karyawan"
        subtitle="Perbarui tingkat kompetensi dan catatan skill untuk: {{ $skill->employee->name }}"
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'HR & Karyawan', 'url' => route('hris.index')], ['label' => 'Skills', 'url' => route('employee-skills.index')], ['label' => 'Edit']]"
    />

    <div class="rounded-lg border border-[#D1D5DB] bg-white p-6 shadow-xs">
        <form method="post" action="{{ route('employee-skills.update', $skill) }}" class="space-y-5">
            @csrf
            @method('put')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Karyawan <span class="text-red-500">*</span>
                    </label>
                    <select name="employee_id" required
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                        @foreach($employees as $e)
                            <option value="{{ $e->id }}" @selected($skill->employee_id === $e->id)>{{ $e->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Tingkat Keahlian / Level <span class="text-red-500">*</span>
                    </label>
                    <select name="level" required
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                        <option value="basic" @selected($skill->level === 'basic')>Basic (Dasar)</option>
                        <option value="intermediate" @selected($skill->level === 'intermediate')>Intermediate (Menengah)</option>
                        <option value="advanced" @selected($skill->level === 'advanced')>Advanced (Mahir)</option>
                        <option value="expert" @selected($skill->level === 'expert')>Expert (Ahli)</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nama Keahlian / Skill <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="skill" value="{{ old('skill', $skill->skill) }}" required
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Catatan Tambahan / Sertifikasi
                    </label>
                    <textarea name="notes" rows="3"
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">{{ old('notes', $skill->notes) }}</textarea>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-4 py-2 text-xs font-semibold text-white shadow-xs hover:bg-[#001B50] transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                    Perbarui Skill
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
