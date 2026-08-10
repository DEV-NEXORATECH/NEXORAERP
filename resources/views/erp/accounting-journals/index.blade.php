@extends('layouts.erp', ['activePage' => 'accounting-journals', 'pageTitle' => 'Accounting Journals'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5" id="accounting-journals">
    <div class="section-head">
        <h2>Accounting Journals</h2>
        <a href="{{ route('accounting-journals.create-page') }}" class="button ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
            Tambah Journal
        </a>
    </div>

    <form method="get" action="{{ route('accounting-journals.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="search" placeholder="Cari nama/kode..." value="{{ request('search') }}" class="field-input">
        <select name="type" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700">
            <option value="">Semua Tipe</option>
            @foreach(['sales' => 'Sales', 'purchase' => 'Purchase', 'bank' => 'Bank', 'cash' => 'Cash', 'general' => 'General', 'opening' => 'Opening', 'adjustment' => 'Adjustment', 'asset' => 'Asset'] as $key => $label)
                <option value="{{ $key }}" {{ request('type') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <div class="flex gap-2 items-end">
            <button class="filter-btn">Filter</button>
            <a href="{{ route('accounting-journals.index') }}" class="btn-outline">Reset</a>
        </div>
    </form>

    <table>
        <thead><tr><th>Kode</th><th>Nama</th><th>Tipe</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($journals as $journal)
            <tr>
                <td class="font-mono text-xs font-bold">{{ $journal->code }}</td>
                <td class="font-bold">{{ $journal->name }}</td>
                <td class="capitalize">{{ $journal->type }}</td>
                <td>
                    <span class="rounded-full px-2 py-0.5 text-xs font-bold {{ $journal->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                        {{ $journal->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="actions">
                    <a href="{{ route('accounting-journals.edit-page', $journal) }}" class="mini ghost">Edit</a>
                    <form method="post" action="{{ route('accounting-journals.destroy', $journal) }}" class="inline">@csrf @method('delete')<button class="mini danger">Delete</button></form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="py-8 text-center text-slate-400">Belum ada journal.</td></tr>
            @endforelse
        </tbody>
    </table>

    @if($journals->hasPages())
    <div class="pager">
        <span>Menampilkan {{ $journals->firstItem() }}-{{ $journals->lastItem() }} dari {{ $journals->total() }}</span>
        {{ $journals->links() }}
    </div>
    @endif
</section>
@endsection
