@extends('layouts.erp', ['activePage' => 'currency-variances.index', 'pageTitle' => 'Currency Variances'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#0B2345]/5" id="currency-variances">
    <div class="section-head">
        <h2>Currency Variances</h2>
        @if($can('admin', 'finance'))
        <a href="{{ route('currency-variances.create-page') }}" class="button ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>

    <form method="get" action="{{ route('currency-variances.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="field-input">
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="field-input">
        <div class="flex gap-2 items-end">
            <button class="filter-btn">Filter</button>
            <a href="{{ route('currency-variances.index') }}" class="btn-outline">Reset</a>
        </div>
    </form>

    <table>
        <thead>
            <tr><th>Rate</th><th>Variance %</th><th>Variance Amount</th><th>Periode</th><th>Notes</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($variances as $row)
                <tr>
                    <td class="font-bold">{{ $row->rate?->from_currency }}→{{ $row->rate?->to_currency }}<br><span class="muted">{{ $row->rate?->rate_date }}</span></td>
                    <td>{{ $row->variance_percent !== null ? number_format($row->variance_percent, 2) . '%' : '-' }}</td>
                    <td>{{ $row->variance_amount !== null ? $rp($row->variance_amount) : '-' }}</td>
                    <td>{{ $row->period ?? '-' }}</td>
                    <td>{{ Str::limit($row->notes, 40) ?? '-' }}</td>
                    <td class="actions">
                        @if($can('admin', 'finance'))<a class="button mini ghost" href="{{ route('currency-variances.edit-page', $row) }}">Edit</a>@endif
                        @if($can('admin', 'finance'))<form method="post" action="{{ route('currency-variances.destroy', $row) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="py-8 text-center text-slate-500">Belum ada currency variance.</td></tr>
            @endforelse
        </tbody>
    </table>

    @if($variances->hasPages())
    <div class="pager">
        <span>Menampilkan {{ $variances->firstItem() }}-{{ $variances->lastItem() }} dari {{ $variances->total() }}</span>
        {{ $variances->links() }}
    </div>
    @endif
</section>
@endsection
