@extends('layouts.erp', ['activePage' => 'sales', 'pageTitle' => 'Buat Proposal Baru'])

@section('content')
<div class="space-y-5 max-w-4xl">
    <x-erp.page-header
        title="Buat Proposal Penawaran Harga"
        subtitle="Dokumen komersial resmi untuk proyek klien dengan rincian nilai penawaran, masa berlaku, dan scope pekerjaan."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Sales & CRM', 'url' => route('sales-crm.index')], ['label' => 'Proposals', 'url' => route('sales.index')], ['label' => 'Buat Proposal']]"
    />

    <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-xs">
        <form method="post" action="{{ route('proposals.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nomor Dokumen Proposal
                    </label>
                    <input type="text" name="number" value="{{ old('number') }}" placeholder="Otomatis digenerate jika kosong"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 placeholder-slate-400 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Proyek Terkait <span class="text-red-500">*</span>
                    </label>
                    <select name="project_id" required
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                        <option value="">-- Pilih Proyek --</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" @selected(old('project_id') == $project->id)>
                                {{ $project->code }} - {{ $project->name }} ({{ $project->client?->name ?? 'Internal' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Judul Proposal <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="Contoh: Proposal Pengembangan Portal Enterprise & Mobile Apps" required autofocus
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                    @error('title')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Total Nilai Penawaran (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="amount" value="{{ old('amount', 0) }}" min="0" step="1" required
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Status Proposal <span class="text-red-500">*</span>
                    </label>
                    <select name="status" required
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                        <option value="draft" @selected(old('status') === 'draft')>Draft (Penyusunan)</option>
                        <option value="sent" @selected(old('status') === 'sent')>Sent (Terkirim ke Klien)</option>
                        <option value="approved" @selected(old('status') === 'approved')>Approved (Disetujui Klien)</option>
                        <option value="rejected" @selected(old('status') === 'rejected')>Rejected (Ditolak)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Masa Berlaku Penawaran (Valid Until)
                    </label>
                    <input type="date" name="valid_until" value="{{ old('valid_until') }}"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Unggah Dokumen Ditandatangani (Signed PDF)
                    </label>
                    <input type="file" name="signed_file" accept=".pdf,.png,.jpg,.jpeg"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[11px] file:bg-slate-100 file:font-semibold">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Ruang Lingkup Pekerjaan (Scope of Work)
                    </label>
                    <textarea name="scope" rows="3" placeholder="Rincian modul, deliverables, jadwal delivery, dan batasan pekerjaan..."
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">{{ old('scope') }}</textarea>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-4 py-2 text-xs font-semibold text-white shadow-xs hover:bg-[#001B50] transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                    Simpan Dokumen Proposal
                </button>
                <a href="{{ route('sales.index') }}" class="inline-flex items-center gap-1.5 rounded-md border border-slate-300 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
