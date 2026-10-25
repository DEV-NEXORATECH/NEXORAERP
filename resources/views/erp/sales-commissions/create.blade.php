@extends('layouts.erp', ['activePage' => 'sales-commissions.create-page', 'pageTitle' => 'Tambah Komisi Sales'])

@section('content')
<div class="space-y-5 max-w-3xl">
    <x-erp.page-header
        title="Hitung Komisi Sales Baru"
        subtitle="Alokasikan reward komisi penjualan berdasarkan persentase closing transaksi deal."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Sales & CRM', 'url' => route('sales-crm.index')], ['label' => 'Sales Commissions', 'url' => route('sales-commissions.index')], ['label' => 'Tambah Komisi']]"
    />

    <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-xs">
        <form method="post" action="{{ route('sales-commissions.store') }}" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Sales Representative <span class="text-red-500">*</span>
                    </label>
                    <select name="user_id" required
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                        <option value="">-- Pilih Sales User --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Periode Komisi (YYYY-MM) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="period" value="{{ old('period', date('Y-m')) }}" placeholder="2026-06" required
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Basis Nilai Penjualan (Base Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="base_amount" value="{{ old('base_amount', 0) }}" min="0" step="1" required
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Rate Komisi (%) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="rate" value="{{ old('rate', 2.5) }}" min="0" max="100" step="0.01" required
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Status Pembayaran Komisi <span class="text-red-500">*</span>
                    </label>
                    <select name="status" required
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                        <option value="draft" @selected(old('status') === 'draft')>Draft (Kalkulasi Awal)</option>
                        <option value="approved" @selected(old('status') === 'approved')>Approved (Disetujui Finance)</option>
                        <option value="paid" @selected(old('status') === 'paid')>Paid (Telah Dibayarkan)</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-4 py-2 text-xs font-semibold text-white shadow-xs hover:bg-[#001B50] transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                    Simpan Komisi
                </button>
                <a href="{{ route('sales-commissions.index') }}" class="inline-flex items-center gap-1.5 rounded-md border border-slate-300 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
