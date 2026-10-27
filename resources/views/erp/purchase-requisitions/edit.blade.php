@extends('layouts.erp', ['activePage' => 'purchase-requisitions', 'pageTitle' => 'Edit Purchase Requisition'])

@section('content')
<div class="erp-form-page space-y-5 max-w-4xl">
    <x-erp.page-header
        title="Edit Purchase Requisition (PR)"
        subtitle="Perbarui estimasi nilai pengadaan, tanggal kebutuhan, dan status permohonan: {{ $requisition->number }} - {{ $requisition->title }}"
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Procurement', 'url' => route('procurement.index')], ['label' => 'Purchase Requisitions', 'url' => route('purchase-requisitions.index')], ['label' => 'Edit']]"
    />

    <div class="rounded-lg border border-[#D1D5DB] bg-white p-6 shadow-xs">
        <form method="post" action="{{ route('purchase-requisitions.update', $requisition) }}" class="space-y-5">
            @csrf
            @method('put')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Pemohon (Requester) <span class="text-red-500">*</span>
                    </label>
                    <select name="requester_id" required
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected($requisition->requester_id === $user->id)>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Departemen Pemohon <span class="text-red-500">*</span>
                    </label>
                    <select name="department_id" required
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" @selected($requisition->department_id === $department->id)>{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Judul Permintaan Pengadaan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title', $requisition->title) }}" required autofocus
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                    @error('title')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Estimasi Anggaran / Nilai PR (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="amount" value="{{ old('amount', $requisition->amount) }}" min="0" step="1" required
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Target Tanggal Dibutuhkan (Required Date)
                    </label>
                    <input type="date" name="required_date" value="{{ old('required_date', $requisition->required_date?->format('Y-m-d')) }}"
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Status Permohonan <span class="text-red-500">*</span>
                    </label>
                    <select name="status" required
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                        <option value="draft" @selected($requisition->status === 'draft')>Draft (Penyusunan)</option>
                        <option value="submitted" @selected($requisition->status === 'submitted')>Submitted (Diajukan untuk Approval)</option>
                        <option value="approved" @selected($requisition->status === 'approved')>Approved (Disetujui)</option>
                        <option value="rejected" @selected($requisition->status === 'rejected')>Rejected (Ditolak)</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Alasan / Justifikasi Pengadaan
                    </label>
                    <textarea name="reason" rows="3"
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">{{ old('reason', $requisition->reason) }}</textarea>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-4 py-2 text-xs font-semibold text-white shadow-xs hover:bg-[#001B50] transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                    Perbarui Purchase Requisition
                </button>
                <a href="{{ route('purchase-requisitions.index') }}" class="inline-flex items-center gap-1.5 rounded-md border border-[#D1D5DB] bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-[#F3F4F6] transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
