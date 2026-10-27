@extends('layouts.erp', ['activePage' => 'sales', 'pageTitle' => 'Edit Proposal'])

@section('content')
<div class="space-y-5 max-w-4xl">
    <x-erp.page-header
        title="Edit Dokumen Proposal"
        subtitle="Perbarui nilai penawaran komersial, deliverables, dan status approval: {{ $proposal->number ?? $proposal->title }}"
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Sales & CRM', 'url' => route('sales-crm.index')], ['label' => 'Proposals', 'url' => route('sales.index')], ['label' => 'Edit']]"
    />

    <div class="rounded-lg border border-[#D1D5DB] bg-white p-6 shadow-xs">
        <form method="post" action="{{ route('proposals.update', $proposal) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('put')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nomor Dokumen Proposal
                    </label>
                    <input type="text" name="number" value="{{ old('number', $proposal->number) }}"
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Proyek Terkait <span class="text-red-500">*</span>
                    </label>
                    <select name="project_id" required
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" @selected($proposal->project_id === $project->id)>
                                {{ $project->code }} - {{ $project->name }} ({{ $project->client?->name ?? 'Internal' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Judul Proposal <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title', $proposal->title) }}" required autofocus
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                    @error('title')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Total Nilai Penawaran (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="amount" value="{{ old('amount', $proposal->amount) }}" min="0" step="1" required
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Status Proposal <span class="text-red-500">*</span>
                    </label>
                    <select name="status" required
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                        <option value="draft" @selected($proposal->status === 'draft')>Draft (Penyusunan)</option>
                        <option value="sent" @selected($proposal->status === 'sent')>Sent (Terkirim ke Klien)</option>
                        <option value="approved" @selected($proposal->status === 'approved')>Approved (Disetujui Klien)</option>
                        <option value="rejected" @selected($proposal->status === 'rejected')>Rejected (Ditolak)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Masa Berlaku Penawaran (Valid Until)
                    </label>
                    <input type="date" name="valid_until" value="{{ old('valid_until', $proposal->valid_until ? \Carbon\Carbon::parse($proposal->valid_until)->format('Y-m-d') : '') }}"
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Unggah Dokumen Ditandatangani Baru (Signed PDF)
                    </label>
                    <input type="file" name="signed_file" accept=".pdf,.png,.jpg,.jpeg"
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-1.5 text-xs text-slate-900 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[11px] file:bg-slate-100 file:font-semibold">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Ruang Lingkup Pekerjaan (Scope of Work)
                    </label>
                    <textarea name="scope" rows="3"
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">{{ old('scope', $proposal->scope) }}</textarea>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-4 py-2 text-xs font-semibold text-white shadow-xs hover:bg-[#001B50] transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                    Perbarui Dokumen Proposal
                </button>
                <a href="{{ route('sales.index') }}" class="inline-flex items-center gap-1.5 rounded-md border border-[#D1D5DB] bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-[#F3F4F6] transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
