@extends('layouts.erp', ['activePage' => 'currencies', 'pageTitle' => 'Currencies'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5" id="currencies">
    <div class="section-head">
        <h2>Mata Uang</h2>
        <a href="{{ route('currencies.create-page') }}" class="button ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
    </div>

    <form method="get" action="{{ route('currencies.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="search" placeholder="Cari kode/nama..." value="{{ request('search') }}" class="field-input">
        <div class="flex gap-2 items-end">
            <button class="filter-btn">Filter</button>
            <a href="{{ route('currencies.index') }}" class="btn-outline">Reset</a>
        </div>
    </form>

    <table>
        <thead><tr><th>Kode</th><th>Nama</th><th>Simbol</th><th>Rate (ke IDR)</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($currencies as $currency)
            <tr>
                <td class="font-mono text-xs font-bold">{{ $currency->code }}</td>
                <td class="font-bold">{{ $currency->name }}</td>
                <td>{{ $currency->symbol }}</td>
                <td>{{ number_format((float) $currency->rate, 4) }}</td>
                <td>
                    <span class="rounded-full px-2 py-0.5 text-xs font-bold {{ $currency->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                        {{ $currency->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="actions">
                    <a href="{{ route('currencies.edit-page', $currency) }}" class="mini ghost">Edit</a>
                    <form method="post" action="{{ route('currencies.destroy', $currency) }}" class="inline">@csrf @method('delete')<button class="mini danger">Delete</button></form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="py-8 text-center text-slate-400">Belum ada mata uang.</td></tr>
            @endforelse
        </tbody>
    </table>

    @if($currencies->hasPages())
    <div class="pager">
        <span>Menampilkan {{ $currencies->firstItem() }}-{{ $currencies->lastItem() }} dari {{ $currencies->total() }}</span>
        {{ $currencies->links() }}
    </div>
    @endif
</section>
@endsection
