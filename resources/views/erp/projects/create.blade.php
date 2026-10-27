@extends('layouts.erp', ['activePage' => 'project-create', 'pageTitle' => 'Tambah Project'])

@section('content')
<div class="max-w-4xl mx-auto space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-[#0F172A] tracking-tight">Tambah Proyek Baru</h1>
            <p class="text-xs text-[#475569]">Lengkapi formulir di bawah ini dengan data yang valid</p>
        </div>
        <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-[#475569] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F8FAFC] transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg border border-[#D1D5DB] p-6 shadow-sm">
        <form method="post" action="{{ route('projects.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2 space-y-4">
            
            @csrf
            <div class="grid gap-1.5"><label>Kode</label><input name="code" placeholder="Auto jika kosong" class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]"></div>
            <div class="grid gap-1.5"><label>Status</label><select name="status" class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]"><option>planning</option><option>active</option><option>done</option><option>hold</option></select></div>
            <div class="grid gap-1.5"><label>Nama Project</label><input name="name" required class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]"></div>
            <div class="grid gap-1.5"><label>Client</label><select name="client_id" class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]"><option value="">Manual</option>@foreach ($clients as $client)<option value="{{ $client->id }}">{{ $client->name }}</option>@endforeach</select></div>
            <div class="grid gap-1.5"><label>Client Manual</label><input name="client" class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]"></div>
            <div class="grid gap-1.5"><label>Budget</label><input name="budget" type="number" min="0" value="0" class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]"></div>
            <div class="grid gap-1.5"><label>Nilai Kontrak</label><input name="contract_value" type="number" min="0" value="0" class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]"></div>
            <div class="grid gap-1.5"><label>Mulai</label><input name="start_date" type="date" class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]"></div>
            <div class="grid gap-1.5"><label>Selesai</label><input name="end_date" type="date" class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]"></div>
            <div class="grid gap-1.5 md:col-span-2"><label>Upload Kontrak</label><input name="contract_file" type="file" class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]"></div>
            <div class="mt-4 flex items-center gap-3 md:col-span-2">
                <button class="btn">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Simpan Project
                </button>
                <a class="button ghost" href="{{ route('projects.index') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali
                </a>
            </div>
        
            <div class="pt-4 border-t border-[#D1D5DB] flex items-center justify-end gap-2">
                <a href="{{ route('projects.index') }}" class="px-3.5 py-2 text-xs font-medium text-[#475569] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F8FAFC] transition">Batal</a>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition shadow-sm">Simpan Project</button>
            </div>
        </form>
    </div>
</div>
@endsection
