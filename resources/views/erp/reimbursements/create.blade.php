@extends('layouts.erp', ['activePage' => 'reimbursements', 'pageTitle' => 'Tambah Reimbursement'])

@section('content')
<div class="space-y-5 max-w-4xl">
    <x-erp.page-header
        title="Ajukan Reimbursement Baru"
        subtitle="Form pengajuan klaim penggantian biaya operasional, transport, makan, atau keperluan dinas."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Reimbursements', 'url' => route('reimbursements.index-page')], ['label' => 'Tambah Klaim']]"
    />

    <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        <form method="post" action="{{ route('reimbursements.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Karyawan Pemohon <span class="text-red-500">*</span>
                    </label>
                    <select name="employee_id" required
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875] transition-colors">
                        <option value="">-- Pilih Karyawan --</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}" @selected(old('employee_id')==$employee->id)>
                                {{ $employee->name }} ({{ $employee->jobPosition?->name ?? 'Staff' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Alokasi Proyek Terkait
                    </label>
                    <select name="project_id"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875] transition-colors">
                        <option value="">-- Non-Project (Biaya Operasional Umum) --</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" @selected(old('project_id')==$project->id)>
                                {{ $project->code }} — {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Kategori Pengeluaran <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="category" value="{{ old('category') }}" required placeholder="Contoh: Transportasi, Akomodasi, Meals, ATK"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 placeholder-slate-400 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875] transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Status Pengajuan <span class="text-red-500">*</span>
                    </label>
                    <select name="status" required
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875] transition-colors">
                        <option value="pending" @selected(old('status')==='pending')>Pending Review</option>
                        <option value="approved" @selected(old('status')==='approved')>Approved</option>
                        <option value="paid" @selected(old('status')==='paid')>Paid (Sudah Ditransfer)</option>
                        <option value="rejected" @selected(old('status')==='rejected')>Rejected</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nominal Klaim (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="amount" min="0" value="{{ old('amount', 0) }}" required
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875] transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Tanggal Pengeluaran <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="expense_date" value="{{ old('expense_date', now()->toDateString()) }}" required
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875] transition-colors">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Unggah Bukti / Nota / Kwitansi (Receipt)
                    </label>
                    <input type="file" name="receipt_file"
                        class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-[#002875]/10 file:text-[#002875] hover:file:bg-[#002875]/20">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Keterangan / Deskripsi Keperluan
                    </label>
                    <textarea name="description" rows="3" placeholder="Jelaskan rincian pengeluaran untuk keperluan dinas apa..."
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875] transition-colors">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-3">
                <a href="{{ route('reimbursements.index-page') }}" class="rounded-md border border-slate-300 bg-white px-4 py-2 text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-[#001B50] focus:outline-none focus:ring-2 focus:ring-[#002875] transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
