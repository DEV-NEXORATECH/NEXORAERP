@extends('layouts.erp', ['activePage' => 'masters', 'pageTitle' => 'Master Data Central'])

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3.5">
            <a href="{{ route('admin.index') }}" class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-md border border-[#E5E7EB] bg-white text-[#475569] transition hover:border-[#002875] hover:text-[#002875]" title="Kembali ke Admin">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-[#002875]">System Administration</span>
                    <span class="text-[#475569]">·</span>
                    <span class="text-[11px] font-medium text-[#475569]">Master Data</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#0F172A]">Master Data Central</h1>
                <p class="mt-0.5 text-xs text-[#475569]">Kelola data referensi master klien, struktur organisasi departemen & jabatan, serta akun kas dan perbankan.</p>
            </div>
        </div>
    </div>

    {{-- 3 Columns Grid --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Card 1: Client Master --}}
        <div class="flex flex-col rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs justify-between">
            <div>
                <div class="mb-4 border-b border-[#E5E7EB] pb-3 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold text-[#0F172A]">Master Klien</h2>
                        <p class="text-xs text-[#475569]">Registrasi data klien untuk project & penagihan</p>
                    </div>
                    <span class="inline-flex items-center rounded-full bg-[#E8EEF5] px-2.5 py-0.5 text-[11px] font-bold text-[#002875]">
                        {{ $clients->total() }} Klien
                    </span>
                </div>

                <div class="flex items-center gap-2 mb-4">
                    <a href="{{ route('clients.create-page') }}" class="btn btn-primary inline-flex h-8 items-center gap-1.5 px-3 text-xs font-semibold shadow-xs flex-1 justify-center">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        + Tambah Klien
                    </a>
                    <a href="{{ route('clients.index') }}" class="btn btn-outline inline-flex h-8 items-center px-3 text-xs font-semibold shadow-xs">
                        Lihat Semua
                    </a>
                </div>

                <h3 class="text-xs font-semibold uppercase tracking-wider text-[#475569] mb-2">Daftar Klien Terkini</h3>
                <div class="divide-y divide-[#E2E8F0] overflow-y-auto max-h-72">
                    @forelse($clients as $client)
                    <div class="py-2.5 flex items-center justify-between gap-2">
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-[#0F172A] truncate">{{ $client->name }}</p>
                            <p class="text-[11px] text-[#475569] truncate">{{ $client->contact_name ?: 'No PIC' }} &bull; {{ $client->email ?: '-' }}</p>
                        </div>
                        <div class="flex items-center gap-1">
                            <a data-action="edit" href="{{ route('clients.edit-page', $client) }}" class="erp-action-button inline-flex h-7 w-7 items-center justify-center rounded border border-[#DDD6FE] bg-[#F5F3FF] text-[#7C3AED] hover:bg-[#EDE9FE]" title="Edit">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form method="post" action="{{ route('masters.destroy', ['clients', $client->id]) }}" class="inline erp-action-form" data-confirm-type="delete" data-confirm-title="Hapus data" data-confirm-text="Hapus klien {{ $client->name }}?">
                                @csrf @method('delete')
                                <button data-action="delete" type="submit" class="erp-action-button inline-flex h-7 w-7 items-center justify-center rounded border border-[#FECACA] bg-[#FEF2F2] text-[#DC2626] hover:bg-[#FEE2E2]" title="Hapus">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <p class="text-xs text-[#94A3B8] py-4 text-center">Belum ada data klien.</p>
                    @endforelse
                </div>
            </div>
            <x-erp.pagination :paginator="$clients" />
        </div>

        {{-- Card 2: HR Master --}}
        <div class="flex flex-col rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs justify-between">
            <div>
                <div class="mb-4 border-b border-[#E5E7EB] pb-3">
                    <h2 class="text-sm font-bold text-[#0F172A]">Master HR & Organisasi</h2>
                    <p class="text-xs text-[#475569]">Departemen dan jabatan struktur organisasi</p>
                </div>

                <div class="grid grid-cols-2 gap-2 mb-4">
                    <a href="{{ route('departments.create-page') }}" class="btn btn-primary inline-flex h-8 items-center justify-center gap-1 px-2.5 text-xs font-semibold shadow-xs">
                        + Departemen
                    </a>
                    <a href="{{ route('job-positions.create-page') }}" class="btn btn-outline inline-flex h-8 items-center justify-center gap-1 px-2.5 text-xs font-semibold shadow-xs">
                        + Jabatan
                    </a>
                </div>

                <div class="space-y-4">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-xs font-semibold uppercase tracking-wider text-[#475569]">Departemen</h3>
                            <a href="{{ route('departments.index') }}" class="text-[11px] font-semibold text-[#002875] hover:underline">Semua &rarr;</a>
                        </div>
                        <div class="divide-y divide-[#E2E8F0] overflow-y-auto max-h-36">
                            @foreach($departments as $department)
                            <div class="py-1.5 flex items-center justify-between gap-2 text-xs">
                                <span class="font-medium text-[#0F172A] truncate">{{ $department->name }}</span>
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('departments.edit-page', $department) }}" class="inline-flex h-6 w-6 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE]">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="border-t border-[#E5E7EB] pt-3">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-xs font-semibold uppercase tracking-wider text-[#475569]">Jabatan</h3>
                            <a href="{{ route('job-positions.index') }}" class="text-[11px] font-semibold text-[#002875] hover:underline">Semua &rarr;</a>
                        </div>
                        <div class="divide-y divide-[#E2E8F0] overflow-y-auto max-h-36">
                            @foreach($jobPositions as $position)
                            <div class="py-1.5 flex items-center justify-between gap-2 text-xs">
                                <span class="font-medium text-[#475569] truncate">{{ $position->name }}</span>
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('job-positions.edit-page', $position) }}" class="inline-flex h-6 w-6 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE]">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: Finance Master --}}
        <div class="flex flex-col rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs justify-between">
            <div>
                <div class="mb-4 border-b border-[#E5E7EB] pb-3">
                    <h2 class="text-sm font-bold text-[#0F172A]">Master Kas & Bank</h2>
                    <p class="text-xs text-[#475569]">Rekening bank dan klasifikasi biaya pengeluaran</p>
                </div>

                <div class="flex items-center gap-2 mb-4">
                    <a href="{{ route('bank-accounts.create-page') }}" class="btn btn-primary inline-flex h-8 items-center justify-center gap-1 px-3 text-xs font-semibold shadow-xs flex-1">
                        + Tambah Akun Bank
                    </a>
                    <a href="{{ route('bank-accounts.index') }}" class="btn btn-outline inline-flex h-8 items-center px-3 text-xs font-semibold shadow-xs">
                        Semua Akun
                    </a>
                </div>

                <h3 class="text-xs font-semibold uppercase tracking-wider text-[#475569] mb-2">Rekening Bank Terdaftar</h3>
                <div class="divide-y divide-[#E2E8F0] overflow-y-auto max-h-72">
                    @foreach($bankAccounts as $bank)
                    <div class="py-2.5 flex items-center justify-between gap-2">
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-[#0F172A] truncate">{{ $bank->name }}</p>
                            <p class="text-[11px] text-[#475569] truncate">{{ $bank->bank_name }} &bull; {{ $bank->account_number }}</p>
                        </div>
                        <div class="flex items-center gap-1">
                            <a data-action="edit" href="{{ route('bank-accounts.edit-page', $bank) }}" class="erp-action-button inline-flex h-7 w-7 items-center justify-center rounded border border-[#DDD6FE] bg-[#F5F3FF] text-[#7C3AED] hover:bg-[#EDE9FE]" title="Edit">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
