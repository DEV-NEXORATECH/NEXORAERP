@extends('layouts.erp', ['activePage' => 'fiscal-periods-create', 'pageTitle' => 'Tambah Periode Fiskal'])

@section('content')
<div class="max-w-4xl mx-auto space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Tambah Periode</h1>
            <p class="text-xs text-gray-500">Lengkapi formulir di bawah ini dengan data yang valid</p>
        </div>
        <a href="{{ route('fiscal-periods.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
        <form method="post" action="{{ route('fiscal-periods.store') }}" class="grid max-w-2xl gap-4" class="space-y-4">
            
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Kode
                <input name="code" placeholder="2026-01" required value="{{ old('code') }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">
            </label>
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Nama
                <input name="name" placeholder="2026 - Bulan 01" required value="{{ old('name') }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">
            </label>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Tanggal Mulai
                <input type="date" name="start_date" required value="{{ old('start_date') }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">
            </label>
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Tanggal Selesai
                <input type="date" name="end_date" required value="{{ old('end_date') }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">
            </label>
        </div>
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Status
            <select name="status" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700">
                <option value="open" {{ old('status', 'open') === 'open' ? 'selected' : '' }}>Open</option>
                <option value="closed" {{ old('status') === 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
        </label>
        
    
            <div class="pt-4 border-t border-gray-200 flex items-center justify-end gap-2">
                <a href="{{ route('fiscal-periods.index') }}" class="px-3.5 py-2 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Batal</a>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition shadow-sm">Tambah Periode</button>
            </div>
        </form>
    </div>
</div>
@endsection
