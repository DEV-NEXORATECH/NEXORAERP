@extends('layouts.erp', ['activePage' => 'payment-terms', 'pageTitle' => 'Payment Terms'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5" id="payment-terms">
    <div class="section-head">
        <h2>Payment Terms</h2>
        <a href="{{ route('payment-terms.create-page') }}" class="button ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
    </div>

    <form method="get" action="{{ route('payment-terms.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="search" placeholder="Cari nama/kode..." value="{{ request('search') }}" class="field-input">
        <div class="flex gap-2 items-end">
            <button class="filter-btn">Filter</button>
            <a href="{{ route('payment-terms.index') }}" class="btn-outline">Reset</a>
        </div>
    </form>

    <table>
        <thead><tr><th>Kode</th><th>Nama</th><th>Tempo</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($terms as $term)
            <tr>
                <td class="font-mono text-xs font-bold">{{ $term->code }}</td>
                <td class="font-bold">{{ $term->name }}</td>
                <td>{{ $term->due_type === 'month' ? $term->due_days . ' bulan' : $term->due_days . ' hari' }}</td>
                <td>
                    <span class="rounded-full px-2 py-0.5 text-xs font-bold {{ $term->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                        {{ $term->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="actions">
                    <a href="{{ route('payment-terms.edit-page', $term) }}" class="mini ghost">Edit</a>
                    <form method="post" action="{{ route('payment-terms.destroy', $term) }}" class="inline">@csrf @method('delete')<button class="mini danger">Delete</button></form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="py-8 text-center text-slate-400">Belum ada payment term.</td></tr>
            @endforelse
        </tbody>
    </table>

    @if($terms->hasPages())
    <div class="pager">
        <span>Menampilkan {{ $terms->firstItem() }}-{{ $terms->lastItem() }} dari {{ $terms->total() }}</span>
        {{ $terms->links() }}
    </div>
    @endif
</section>
@endsection
