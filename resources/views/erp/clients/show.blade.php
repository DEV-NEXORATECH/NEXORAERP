@extends('layouts.erp', ['activePage' => 'masters', 'pageTitle' => $client->name.' Detail'])

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3.5">
            <a href="{{ route('clients.index') }}" class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-md border border-[#E5E7EB] bg-white text-[#475569] transition hover:border-[#002875] hover:text-[#002875]" title="Kembali">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-[#002875]">Master Data</span>
                    <span class="text-[#475569]">·</span>
                    <span class="text-[11px] font-medium text-[#475569]">Clients</span>
                    <span class="text-[#475569]">·</span>
                    <span class="text-[11px] font-bold text-[#0F172A]">{{ $client->name }}</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#0F172A] mt-0.5">{{ $client->name }}</h1>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('clients.edit-page', $client) }}" class="inline-flex h-9 items-center gap-1.5 px-3.5 rounded-md text-xs font-semibold text-white bg-[#0369A1] hover:bg-[#0284C7] transition shadow-xs">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit Klien
            </a>
        </div>
    </div>

    {{-- Details 2 Columns --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs">
            <h2 class="text-sm font-bold text-[#0F172A] border-b border-[#E5E7EB] pb-3 mb-4">Informasi Kontak & Perusahaan</h2>
            <dl class="space-y-3 text-xs">
                <div>
                    <dt class="text-[#64748B]">Nama PIC / Kontak</dt>
                    <dd class="font-bold text-[#0F172A] mt-0.5">{{ $client->contact_name ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-[#64748B]">Email Klien</dt>
                    <dd class="font-semibold text-[#0F172A] mt-0.5">{{ $client->email ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-[#64748B]">Telepon</dt>
                    <dd class="font-semibold text-[#0F172A] mt-0.5">{{ $client->phone ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-[#64748B]">Alamat Kantor</dt>
                    <dd class="font-medium text-[#334155] mt-0.5">{{ $client->address ?: '-' }}</dd>
                </div>
            </dl>
        </div>

        <div class="lg:col-span-2 rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs">
            <h2 class="text-sm font-bold text-[#0F172A] border-b border-[#E5E7EB] pb-3 mb-4">Proyek Klien Terdaftar</h2>
            <div class="overflow-x-auto">
                <table class="table-folk w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-[#E5E7EB] bg-[#F8FAFC]">
                            <th class="p-2.5 font-semibold text-[#475569]">Kode</th>
                            <th class="p-2.5 font-semibold text-[#475569]">Nama Proyek</th>
                            <th class="p-2.5 font-semibold text-[#475569] text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E5E7EB]">
                        @forelse($client->projects as $proj)
                            <tr>
                                <td class="p-2.5 font-bold text-[#002875]">{{ $proj->code }}</td>
                                <td class="p-2.5 text-[#0F172A] font-medium">{{ $proj->name }}</td>
                                <td class="p-2.5 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-blue-50 text-[#002875]">
                                        {{ $proj->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-6 text-center text-[#94A3B8]">Belum ada proyek aktif untuk klien ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection