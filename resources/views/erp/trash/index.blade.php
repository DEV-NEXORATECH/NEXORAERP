@extends('layouts.erp', ['activePage' => 'trash', 'pageTitle' => 'Trash Restore'])

@section('content')
<div class="space-y-4">
    <x-erp.page-header
        title="Keranjang Sampah & Pemulihan (Trash)"
        subtitle="Daftar entitas data yang telah dihapus sementara (soft-deleted). Anda dapat memulihkan (restore) data ke posisi semula."
        :breadcrumbs="[['label' =>
        <x-slot:actions>
            <x-erp.toolbar-actions module="trash" />
        </x-slot:actions> 'Dashboard', 'url' => route('dashboard')], ['label' => 'Administration', 'url' => route('admin.index')], ['label' => 'Trash']]"
    />

    <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-xs">
        
    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-card">
        <form method="get" action="{{ route('trash.index') }}" class="filter-toolbar">
            
            <div>
                <select name="type" class="rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                    <option value="">Semua Tipe Data</option>
                    <option value="projects" @selected(request('type')==='projects')>Projects</option>
                    <option value="proposals" @selected(request('type')==='proposals')>Proposals</option>
                    <option value="invoices" @selected(request('type')==='invoices')>Invoices</option>
                    <option value="users" @selected(request('type')==='users')>Users</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-[#001B50] transition-colors">
                    Filter
                </button>
                @if(request('type'))
                    <a href="{{ route('trash.index') }}" class="inline-flex items-center rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        
        </form>
    </div>

    </div>

    <div class="rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Modul / Tipe</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Identifier / Nama Record</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @if($hasTrash)
                        @foreach($trash as $type => $items)
                            @foreach($items as $item)
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold border bg-slate-50 text-slate-700 border-slate-200 uppercase">
                                            {{ $type }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-slate-900 font-semibold">
                                        {{ $item->name ?? $item->title ?? $item->number ?? $item->code ?? ('ID #'.$item->id) }}
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <form method="post" action="{{ route('trash.restore', [$type, $item->id]) }}" class="inline" onsubmit="return confirm('Pulihkan data ini kembali ke sistem aktif?');">
                                            @csrf
                                            @method('patch')
                                            <button type="submit" class="inline-flex items-center gap-1 rounded border border-[#002875] bg-white px-2.5 py-1 text-[11px] font-semibold text-[#002875] hover:bg-[#002875]/5 transition-colors">
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                                                Pulihkan (Restore)
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-slate-500">
                                <x-erp.empty-state message="Keranjang sampah kosong. Tidak ada data terhapus." />
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    @foreach($trash as $type => $items)
        <x-erp.pagination :paginator="$items" />
    @endforeach
</div>

<x-erp.import-modal module="trash" title="Impor Data Trash" />
@endsection