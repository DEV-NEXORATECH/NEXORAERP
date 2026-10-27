@extends('layouts.erp', ['activePage' => 'cashflow-create', 'pageTitle' => 'Tambah Cashflow'])

@section('content')
<div class="max-w-4xl mx-auto space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-[#0F172A] tracking-tight">Tambah Transaksi Cashflow</h1>
            <p class="text-xs text-[#475569]">Lengkapi formulir di bawah ini dengan data yang valid</p>
        </div>
        <a href="{{ route('cashflows.index-page') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-[#475569] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F8FAFC] transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg border border-[#D1D5DB] p-6 shadow-sm">
        <form method="post" action="{{ route('cashflows.store') }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2" class="space-y-4">
            
            @csrf

            {{-- Type --}}
            <div class="grid gap-1.5">
                <label>Tipe Transaksi <span class="text-red-500">*</span></label>
                <select name="type" id="cf-type" required class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]">
                    <option value="income" @selected(old('type') === 'income')>Income — Pemasukan</option>
                    <option value="expense" @selected(old('type', 'expense') === 'expense')>Expense — Pengeluaran</option>
                </select>
            </div>

            {{-- Transaction Date --}}
            <div class="grid gap-1.5">
                <label>Tanggal Transaksi <span class="text-red-500">*</span></label>
                <input name="transaction_date" type="date" value="{{ old('transaction_date', date('Y-m-d')) }}" required class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]">
            </div>

            {{-- Amount --}}
            <div class="grid gap-1.5">
                <label>Jumlah (Rp) <span class="text-red-500">*</span></label>
                <input name="amount" type="number" min="0" step="1" value="{{ old('amount') }}" placeholder="0" required class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]">
            </div>

            {{-- Project --}}
            <div class="grid gap-1.5">
                <label>Project</label>
                <select name="project_id" class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]">
                    <option value="">Company (non-project)</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}" @selected(old('project_id') == $project->id)>{{ $project->code }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Category --}}
            <div class="grid gap-1.5">
                <label>Kategori <span class="text-red-500">*</span></label>
                <input name="category" value="{{ old('category') }}" placeholder="Contoh: Bayar Listrik, Gaji, Terima DP..." required maxlength="100" class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]">
            </div>

            {{-- Cost Type --}}
            <div class="grid gap-1.5">
                <label>Cost Type <span class="text-red-500">*</span></label>
                <select name="cost_type" required class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]">
                    <option value="client_payment" @selected(old('cost_type') === 'client_payment')>Client Payment — pembayaran dari klien</option>
                    <option value="operational" @selected(old('cost_type') === 'operational')>Operational — biaya operasional</option>
                    <option value="salary" @selected(old('cost_type') === 'salary')>Salary — penggajian</option>
                    <option value="reimbursement" @selected(old('cost_type') === 'reimbursement')>Reimbursement — penggantian biaya</option>
                    <option value="vendor" @selected(old('cost_type') === 'vendor')>Vendor — pembayaran vendor</option>
                    <option value="subcontractor" @selected(old('cost_type') === 'subcontractor')>Subcontractor — jasa subkon</option>
                    <option value="tools" @selected(old('cost_type') === 'tools')>Tools — peralatan &amp; lisensi</option>
                    <option value="cloud" @selected(old('cost_type') === 'cloud')>Cloud — hosting &amp; cloud services</option>
                </select>
            </div>

            {{-- Expense Category --}}
            <div class="grid gap-1.5">
                <label>Expense Category</label>
                <select name="expense_category_id" class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]">
                    <option value="">— Pilih Kategori (opsional) —</option>
                    @foreach($expenseCategories as $category)
                        <option value="{{ $category->id }}" @selected(old('expense_category_id') == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Bank / Kas --}}
            <div class="grid gap-1.5">
                <label>Bank / Kas</label>
                <select name="bank_account_id" class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]">
                    <option value="">— Pilih Rekening (opsional) —</option>
                    @foreach($bankAccounts as $bank)
                        <option value="{{ $bank->id }}" @selected(old('bank_account_id') == $bank->id)>{{ $bank->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Vendor --}}
            <div class="grid gap-1.5 md:col-span-2">
                <label>Vendor / Pihak Terkait</label>
                <input name="vendor" value="{{ old('vendor') }}" placeholder="Nama vendor, supplier, atau nama klien..." class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]">
            </div>

            {{-- Description --}}
            <div class="grid gap-1.5 md:col-span-2">
                <label>Deskripsi <span class="text-red-500">*</span></label>
                <input name="description" value="{{ old('description') }}" placeholder="Keterangan detail transaksi..." required maxlength="255" class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]">
            </div>

            {{-- Actions --}}
            <div class="mt-4 flex flex-wrap items-center gap-3 md:col-span-2">
                <button type="submit" class="btn">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                    Simpan Cashflow
                </button>
                <a class="button ghost" href="{{ route('cashflows.index-page') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Kembali
                </a>
            </div>
        
            <div class="pt-4 border-t border-[#D1D5DB] flex items-center justify-end gap-2">
                <a href="{{ route('cashflows.index-page') }}" class="px-3.5 py-2 text-xs font-medium text-[#475569] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F8FAFC] transition">Batal</a>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition shadow-sm">Simpan Cashflow</button>
            </div>
        </form>
    </div>
</div>
@endsection
