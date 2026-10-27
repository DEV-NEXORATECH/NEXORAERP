@extends('layouts.erp', ['activePage' => 'job-positions', 'pageTitle' => 'Edit Jabatan'])

@section('content')
<div class="space-y-5 max-w-2xl">
    <x-erp.page-header
        title="Edit Posisi / Jabatan"
        subtitle="Perbarui nama posisi pekerjaan: {{ $jobPosition->name }}"
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'HR & Organisasi', 'url' => route('hris.index')], ['label' => 'Jabatan', 'url' => route('job-positions.index')], ['label' => 'Edit']]"
    />

    <div class="rounded-lg border border-[#D1D5DB] bg-white p-6 shadow-xs">
        <form method="post" action="{{ route('job-positions.update', $jobPosition) }}" class="space-y-5">
            @csrf
            @method('put')
            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Nama Posisi / Jabatan <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $jobPosition->name) }}" required autofocus
                    class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875] transition-colors">
                @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-4 py-2 text-xs font-semibold text-white shadow-xs hover:bg-[#001B50] focus:outline-none focus:ring-2 focus:ring-[#002875]/20 transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                    Perbarui Jabatan
                </button>
                <a href="{{ route('job-positions.index') }}" class="inline-flex items-center gap-1.5 rounded-md border border-[#D1D5DB] bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-[#F3F4F6] transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
