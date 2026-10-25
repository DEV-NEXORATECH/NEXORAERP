@extends('layouts.erp', ['activePage' => 'clients', 'pageTitle' => 'Clients'])

@section('content')
<div class="space-y-4">
    <x-erp.page-header
        title="Daftar Klien / Customer"
        subtitle="Kelola database partner komersial, data PIC, kontrak, dan riwayat relasi bisnis."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Sales & CRM', 'url' => route('sales-crm.index')], ['label' => 'Klien']]"
    >
        <x-slot:actions>
            @if($can('admin', 'sales', 'finance'))
                <a href="{{ route('clients.create-page') }}" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-3.5 py-2 text-xs font-semibold text-white shadow-xs hover:bg-[#001B50] transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                    Tambah Klien
                </a>
            @endif
        </x-slot:actions>
    </x-erp.page-header>

    <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-xs">
        <form method="get" action="{{ route('clients.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-[200px]">
                <input type="text" name="search" placeholder="Cari nama perusahaan, PIC, email..." value="{{ request('search') }}"
                    class="w-full rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 placeholder-slate-400 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-[#001B50] transition-colors">
                    Filter
                </button>
                @if(request('search'))
                    <a href="{{ route('clients.index') }}" class="inline-flex items-center rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Perusahaan Klien</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">PIC & Kontak</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($clients as $client)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-4 py-3 text-slate-900">
                                <div class="font-bold text-slate-900">{{ $client->name }}</div>
                                <div class="text-[11px] text-slate-500 mt-0.5 line-clamp-1">{{ $client->address ?: 'Alamat belum diatur' }}</div>
                            </td>
                            <td class="px-4 py-3 text-slate-700">
                                <div class="font-medium text-slate-900">{{ $client->contact_name ?: '-' }}</div>
                                <div class="text-[11px] text-slate-500 mt-0.5">
                                    {{ $client->email ?: '-' }} · {{ $client->phone ?: '-' }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    @if($can('admin', 'sales', 'finance'))
                                        <a href="{{ route('clients.edit-page', $client) }}" class="inline-flex items-center gap-1 rounded border border-slate-300 bg-white px-2 py-1 text-[11px] font-medium text-slate-700 hover:bg-slate-50 hover:text-[#002875] transition-colors" title="Edit">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                                            Edit
                                        </a>
                                        @if($can('admin'))
                                            <form method="post" action="{{ route('clients.destroy', $client) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data klien ini?');">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="inline-flex items-center gap-1 rounded border border-red-200 bg-white px-2 py-1 text-[11px] font-medium text-red-600 hover:bg-red-50 transition-colors" title="Hapus">
                                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M10 11v6M14 11v6"/></svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-slate-500">
                                <x-erp.empty-state message="Belum ada data klien terdaftar." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($clients->hasPages())
            <div class="px-4 py-3 border-t border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-600">
                <span>Menampilkan {{ $clients->firstItem() }}-{{ $clients->lastItem() }} dari {{ $clients->total() }} klien</span>
                {{ $clients->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
