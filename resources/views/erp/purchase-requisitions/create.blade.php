@extends('layouts.erp', ['activePage' => 'purchase-requisitions', 'pageTitle' => 'Buat Purchase Requisition'])

@section('content')
<div class="space-y-5 max-w-4xl">
    <x-erp.page-header
        title="Buat Purchase Requisition (PR) Baru"
        subtitle="Form permohonan pengadaan barang atau jasa internal untuk diverifikasi dan disetujui sebelum penerbitan PO."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Procurement', 'url' => route('procurement.index')], ['label' => 'Purchase Requisitions', 'url' => route('purchase-requisitions.index')], ['label' => 'Tambah PR']]"
    />

    <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-xs">
        <form method="post" action="{{ route('purchase-requisitions.store') }}" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Pemohon (Requester) <span class="text-red-500">*</span>
                    </label>
                    <select name="requester_id" required
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                        <option value="">-- Pilih Pemohon --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected(old('requester_id', auth()->id()) == $user->id)>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Departemen Pemohon <span class="text-red-500">*</span>
                    </label>
                    <select name="department_id" required
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                        <option value="">-- Pilih Departemen --</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" @selected(old('department_id') == $department->id)>{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Judul Permintaan Pengadaan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="Contoh: Pengadaan Laptop Development & Perangkat Monitor" required autofocus
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                    @error('title')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Estimasi Anggaran / Nilai PR (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="amount" value="{{ old('amount', 0) }}" min="0" step="1" required
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Target Tanggal Dibutuhkan (Required Date)
                    </label>
                    <input type="date" name="required_date" value="{{ old('required_date') }}"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Status Permohonan <span class="text-red-500">*</span>
                    </label>
                    <select name="status" required
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                        <option value="draft" @selected(old('status') === 'draft')>Draft (Penyusunan)</option>
                        <option value="submitted" @selected(old('status') === 'submitted')>Submitted (Diajukan untuk Approval)</option>
                        <option value="approved" @selected(old('status') === 'approved')>Approved (Disetujui)</option>
                        <option value="rejected" @selected(old('status') === 'rejected')>Rejected (Ditolak)</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Alasan / Justifikasi Pengadaan
                    </label>
                    <textarea name="reason" rows="3" placeholder="Uraikan justifikasi kebutuhan bisnis atau proyek terkait..."
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">{{ old('reason') }}</textarea>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-4 py-2 text-xs font-semibold text-white shadow-xs hover:bg-[#001B50] transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                    Simpan Purchase Requisition
                </button>
                <a href="{{ route('purchase-requisitions.index') }}" class="inline-flex items-center gap-1.5 rounded-md border border-slate-300 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
