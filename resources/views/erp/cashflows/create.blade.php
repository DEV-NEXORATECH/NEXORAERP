@extends('layouts.erp', ['activePage' => 'cashflow-create', 'pageTitle' => 'Tambah Cashflow'])

@section('content')
<section class="section">
    <div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">

        <div class="mb-8 border-b border-[#d7e3ef] pb-5">
            <h2 class="mb-1">Tambah Cashflow</h2>
            <p class="muted">Catat transaksi pemasukan atau pengeluaran.</p>
        </div>

        <form method="post" action="{{ route('cashflows.store') }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
            @csrf

            {{-- Type --}}
            <div class="grid gap-1.5">
                <label>Tipe Transaksi <span class="text-red-500">*</span></label>
                <select name="type" id="cf-type" required>
                    <option value="income" @selected(old('type') === 'income')>Income — Pemasukan</option>
                    <option value="expense" @selected(old('type', 'expense') === 'expense')>Expense — Pengeluaran</option>
                </select>
            </div>

            {{-- Transaction Date --}}
            <div class="grid gap-1.5">
                <label>Tanggal Transaksi <span class="text-red-500">*</span></label>
                <input name="transaction_date" type="date" value="{{ old('transaction_date', date('Y-m-d')) }}" required>
            </div>

            {{-- Amount --}}
            <div class="grid gap-1.5">
                <label>Jumlah (Rp) <span class="text-red-500">*</span></label>
                <input name="amount" type="number" min="0" step="1" value="{{ old('amount') }}" placeholder="0" required>
            </div>

            {{-- Project --}}
            <div class="grid gap-1.5">
                <label>Project</label>
                <select name="project_id">
                    <option value="">Company (non-project)</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}" @selected(old('project_id') == $project->id)>{{ $project->code }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Category --}}
            <div class="grid gap-1.5">
                <label>Kategori <span class="text-red-500">*</span></label>
                <input name="category" value="{{ old('category') }}" placeholder="Contoh: Bayar Listrik, Gaji, Terima DP..." required maxlength="100">
            </div>

            {{-- Cost Type --}}
            <div class="grid gap-1.5">
                <label>Cost Type <span class="text-red-500">*</span></label>
                <select name="cost_type" required>
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
                <select name="expense_category_id">
                    <option value="">— Pilih Kategori (opsional) —</option>
                    @foreach($expenseCategories as $category)
                        <option value="{{ $category->id }}" @selected(old('expense_category_id') == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Bank / Kas --}}
            <div class="grid gap-1.5">
                <label>Bank / Kas</label>
                <select name="bank_account_id">
                    <option value="">— Pilih Rekening (opsional) —</option>
                    @foreach($bankAccounts as $bank)
                        <option value="{{ $bank->id }}" @selected(old('bank_account_id') == $bank->id)>{{ $bank->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Vendor --}}
            <div class="grid gap-1.5 md:col-span-2">
                <label>Vendor / Pihak Terkait</label>
                <input name="vendor" value="{{ old('vendor') }}" placeholder="Nama vendor, supplier, atau nama klien...">
            </div>

            {{-- Description --}}
            <div class="grid gap-1.5 md:col-span-2">
                <label>Deskripsi <span class="text-red-500">*</span></label>
                <input name="description" value="{{ old('description') }}" placeholder="Keterangan detail transaksi..." required maxlength="255">
            </div>

            {{-- Errors --}}
            @if ($errors->any())
            <div class="md:col-span-2 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Actions --}}
            <div class="mt-4 flex flex-wrap items-center gap-3 md:col-span-2">
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                    Simpan Cashflow
                </button>
                <a class="button ghost" href="{{ route('cashflows.index-page') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Kembali
                </a>
            </div>
        </form>
    </div>
</section>
@endsection
