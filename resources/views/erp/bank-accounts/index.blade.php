@extends('layouts.erp', ['activePage' => 'bank-accounts', 'pageTitle' => 'Bank Accounts'])

@section('content')
<div class="space-y-4">
    <x-erp.page-header
        title="Daftar Akun Kas & Bank"
        subtitle="Kelola rekening bank operasional, kas kecil, dan saldo awal keuangan perusahaan."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Finance & Accounting', 'url' => route('finance-suite.index')], ['label' => 'Rekening Bank']]"
    >
        <x-slot:actions>
            <x-erp.toolbar-actions module="bank-accounts" :create-route="route('bank-accounts.create-page')" create-label="+ Tambah Rekening Bank" />
        </x-slot:actions>
    </x-erp.page-header>

    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-panel">
        <div class="filter-panel-header">
            <div class="filter-panel-title">
                <div class="filter-panel-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg></div>
                <h3>Filter & Pencarian</h3>
            </div>
        </div>
        <form method="get" action="{{ route('bank-accounts.index') }}" class="filter-toolbar erp-filter-toolbar">
            
            <div class="relative flex-1 min-w-[200px]">
                <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="filter-search pl-8 pr-3 text-xs">
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-[#001B50] transition-colors">
                    Filter
                </button>
                @if(request('search'))
                    <a href="{{ route('bank-accounts.index') }}" class="inline-flex items-center rounded-md border border-[#D1D5DB] bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-[#F3F4F6] transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        
        </form>
    </div>

    <div class="rounded-lg border border-[#D1D5DB] bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-[#D1D5DB]">
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Nama Akun / Kas</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Lembaga Bank</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">No. Rekening</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider text-right">Saldo Awal</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($bankAccounts as $bank)
                        <tr class="hover:bg-[#F3F4F6]/70 transition-colors">
                            <td class="px-4 py-3 text-slate-900 font-semibold">
                                {{ $bank->name }}
                            </td>
                            <td class="px-4 py-3 text-slate-700">
                                {{ $bank->bank_name ?: '-' }}
                            </td>
                            <td class="px-4 py-3 text-slate-700 font-mono">
                                {{ $bank->account_number ?: '-' }}
                            </td>
                            <td class="px-4 py-3 text-right font-bold text-slate-900">
                                {{ $rp($bank->opening_balance) }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    @if($can('admin', 'finance'))
                                        <a data-action="edit" href="{{ route('bank-accounts.edit-page', $bank) }}" class="erp-action-button inline-flex items-center gap-1 rounded border border-[#D1D5DB] bg-white px-2 py-1 text-[11px] font-medium text-slate-700 hover:bg-[#F3F4F6] hover:text-[#002875] transition-colors" title="Edit">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                                            Edit
                                        </a>
                                        @if($can('admin'))
                                            <form method="post" action="{{ route('bank-accounts.destroy', $bank) }}" class="inline inline erp-action-form" data-confirm-type="delete" data-confirm-title="Hapus data" data-confirm-text="Apakah Anda yakin ingin menghapus rekening bank ini?">
                                                @csrf
                                                @method('delete')
                                                <button data-action="delete" type="submit" class="erp-action-button inline-flex items-center gap-1 rounded border border-red-200 bg-white px-2 py-1 text-[11px] font-medium text-red-600 hover:bg-red-50 transition-colors" title="Hapus">
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
                            <td colspan="5" class="px-4 py-8 text-center text-slate-500">
                                <x-erp.empty-state message="Belum ada data rekening bank/kas." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <x-erp.pagination :paginator="$bankAccounts" />
</div>

<x-erp.import-modal module="bank-accounts" title="Impor Data Bank Accounts" />
@endsection