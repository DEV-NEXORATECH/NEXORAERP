@extends('layouts.erp', ['activePage' => 'fiscal-periods', 'pageTitle' => 'Fiscal Periods'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5" id="fiscal-periods">
    <div class="section-head">
        <h2>Periode Fiskal</h2>
        <a href="{{ route('fiscal-periods.create-page') }}" class="button ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
    </div>

    <form method="get" action="{{ route('fiscal-periods.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="search" placeholder="Cari nama/kode..." value="{{ request('search') }}" class="field-input">
        <select name="status" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700">
            <option value="">Semua Status</option>
            <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
            <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
        </select>
        <div class="flex gap-2 items-end">
            <button class="filter-btn">Filter</button>
            <a href="{{ route('fiscal-periods.index') }}" class="btn-outline">Reset</a>
        </div>
    </form>

    <table>
        <thead><tr><th>Kode</th><th>Nama</th><th>Mulai</th><th>Selesai</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($periods as $period)
            <tr>
                <td class="font-mono text-xs font-bold">{{ $period->code }}</td>
                <td class="font-bold">{{ $period->name }}</td>
                <td>{{ $period->start_date->format('d M Y') }}</td>
                <td>{{ $period->end_date->format('d M Y') }}</td>
                <td>
                    <span class="rounded-full px-2 py-0.5 text-xs font-bold {{ $period->status === 'open' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                        {{ ucfirst($period->status) }}
                    </span>
                </td>
                <td class="actions">
                    <a href="{{ route('fiscal-periods.edit-page', $period) }}" class="mini ghost">Edit</a>
                    <form method="post" action="{{ route('fiscal-periods.toggle', $period) }}" class="inline">
                        @csrf
                        <button class="mini">{{ $period->status === 'open' ? 'Close' : 'Open' }}</button>
                    </form>
                    <form method="post" action="{{ route('fiscal-periods.destroy', $period) }}" class="inline">@csrf @method('delete')<button class="mini danger">Delete</button></form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="py-8 text-center text-slate-400">Belum ada periode fiskal.</td></tr>
            @endforelse
        </tbody>
    </table>

    @if($periods->hasPages())
    <div class="pager">
        <span>Menampilkan {{ $periods->firstItem() }}-{{ $periods->lastItem() }} dari {{ $periods->total() }}</span>
        {{ $periods->links() }}
    </div>
    @endif
</section>
@endsection
