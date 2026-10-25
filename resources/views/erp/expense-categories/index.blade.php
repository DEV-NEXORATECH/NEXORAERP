@extends('layouts.erp', ['activePage' => 'expense-categories', 'pageTitle' => 'Expense Categories'])

@section('content')
<div class="space-y-4">
    <x-erp.page-header
        title="Kategori Pengeluaran & Pemasukan"
        subtitle="Kelola klasifikasi pos biaya proyek dan transaksi kas operasional."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Finance & Accounting', 'url' => route('finance-suite.index')], ['label' => 'Kategori Biaya']]"
    >
        <x-slot:actions>
            @if($can('admin', 'finance'))
                <a href="{{ route('expense-categories.create-page') }}" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-3.5 py-2 text-xs font-semibold text-white shadow-xs hover:bg-[#001B50] transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                    Tambah Kategori
                </a>
            @endif
        </x-slot:actions>
    </x-erp.page-header>

    <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-xs">
        <form method="get" action="{{ route('expense-categories.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-[200px]">
                <input type="text" name="search" placeholder="Cari nama kategori..." value="{{ request('search') }}"
                    class="w-full rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 placeholder-slate-400 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
            </div>
            <div>
                <select name="type" class="rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                    <option value="">Semua Tipe</option>
                    <option value="expense" @selected(request('type') == 'expense')>Expense (Pengeluaran)</option>
                    <option value="income" @selected(request('type') == 'income')>Income (Pemasukan)</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-[#001B50] transition-colors">
                    Filter
                </button>
                @if(request('search') || request('type'))
                    <a href="{{ route('expense-categories.index') }}" class="inline-flex items-center rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
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
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Nama Kategori</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Tipe</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($expenseCategories as $category)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-4 py-3 text-slate-900 font-semibold">
                                {{ $category->name }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold border {{ $category->type === 'income' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200' }}">
                                    {{ ucfirst($category->type) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    @if($can('admin', 'finance'))
                                        <a href="{{ route('expense-categories.edit-page', $category) }}" class="inline-flex items-center gap-1 rounded border border-slate-300 bg-white px-2 py-1 text-[11px] font-medium text-slate-700 hover:bg-slate-50 hover:text-[#002875] transition-colors" title="Edit">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                                            Edit
                                        </a>
                                        @if($can('admin'))
                                            <form method="post" action="{{ route('expense-categories.destroy', $category) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
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
                                <x-erp.empty-state message="Belum ada data kategori biaya." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($expenseCategories->hasPages())
            <div class="px-4 py-3 border-t border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-600">
                <span>Menampilkan {{ $expenseCategories->firstItem() }}-{{ $expenseCategories->lastItem() }} dari {{ $expenseCategories->total() }} kategori</span>
                {{ $expenseCategories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
