@extends('layouts.erp', ['activePage' => 'currency-rates.index', 'pageTitle' => 'Currency Rates'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5" id="currency-rates">
    <div class="section-head">
        <h2>Currency Rates</h2>
        @if($can('admin', 'finance'))
        <a href="{{ route('currency-rates.create-page') }}" class="button ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>

    <form method="get" action="{{ route('currency-rates.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="from_currency" placeholder="Dari Mata Uang..." value="{{ request('from_currency') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 placeholder:text-slate-400 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <input type="text" name="to_currency" placeholder="Ke Mata Uang..." value="{{ request('to_currency') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 placeholder:text-slate-400 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <div class="flex gap-2 items-end">
            <button class="rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#003d78]">Filter</button>
            <a href="{{ route('currency-rates.index') }}" class="rounded-xl border border-[#d7e3ef] bg-white px-5 py-2.5 text-sm font-bold text-[#0059A7] hover:bg-[#f3f8fc]">Reset</a>
        </div>
    </form>

    <table>
        <thead>
            <tr><th>Dari</th><th>Ke</th><th>Rate</th><th>Tanggal</th><th>Sumber</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($rates as $row)
                <tr>
                    <td class="font-bold">{{ $row->from_currency }}</td>
                    <td>{{ $row->to_currency }}</td>
                    <td>{{ number_format($row->rate, 4) }}</td>
                    <td>{{ $row->rate_date }}</td>
                    <td>{{ $row->source ?? '-' }}</td>
                    <td class="actions">
                        @if($can('admin', 'finance'))<a class="button mini ghost" href="{{ route('currency-rates.edit-page', $row) }}">Edit</a>@endif
                        @if($can('admin', 'finance'))<form method="post" action="{{ route('currency-rates.destroy', $row) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="py-8 text-center text-slate-500">Belum ada currency rate.</td></tr>
            @endforelse
        </tbody>
    </table>

    @if($rates->hasPages())
    <div class="pager">
        <span>Menampilkan {{ $rates->firstItem() }}-{{ $rates->lastItem() }} dari {{ $rates->total() }}</span>
        {{ $rates->links() }}
    </div>
    @endif
</section>
@endsection
