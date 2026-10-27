@extends('layouts.erp', ['activePage' => 'accounting-journals-edit', 'pageTitle' => 'Edit Journal'])

@section('content')
<div class="max-w-4xl mx-auto space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-[#0F172A] tracking-tight">Edit Buku Jurnal Akuntansi</h1>
            <p class="text-xs text-[#475569]">Lengkapi formulir di bawah ini dengan data yang valid</p>
        </div>
        <a href="{{ route('accounting-journals.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-[#475569] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F8FAFC] transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg border border-[#D1D5DB] p-6 shadow-sm">
        <form method="post" action="{{ route('accounting-journals.update', $journal) }}" class="grid max-w-2xl gap-4 space-y-4">
            
        @csrf
        @method('put')
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Kode
            <input name="code" placeholder="JSL" required value="{{ old('code', $journal->code) }}" class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]">
        </label>
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Nama
            <input name="name" placeholder="Sales Journal" required value="{{ old('name', $journal->name) }}" class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]">
        </label>
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Tipe
            <select name="type" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700">
                @foreach(['sales', 'purchase', 'bank', 'cash', 'general', 'opening', 'adjustment', 'asset'] as $type)
                    <option value="{{ $type }}" {{ old('type', $journal->type) === $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                @endforeach
            </select>
        </label>
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Akun Default
            <select name="default_chart_account_id" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700">
                <option value="">-</option>
                @foreach($accounts as $account)
                    <option value="{{ $account->id }}" {{ old('default_chart_account_id', $journal->default_chart_account_id) == $account->id ? 'selected' : '' }}>{{ $account->code }} - {{ $account->name }}</option>
                @endforeach
            </select>
        </label>
        <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
            <input type="checkbox" name="is_active" value="1" {{ $journal->is_active ? 'checked' : '' }} class="h-4 w-4">
            Aktif
        </label>
        
    
            <div class="pt-4 border-t border-[#D1D5DB] flex items-center justify-end gap-2">
                <a href="{{ route('accounting-journals.index') }}" class="px-3.5 py-2 text-xs font-medium text-[#475569] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F8FAFC] transition">Batal</a>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
