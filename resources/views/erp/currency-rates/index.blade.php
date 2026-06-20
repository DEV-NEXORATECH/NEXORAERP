@extends('layouts.erp', ['activePage' => 'currency-rates.index', 'pageTitle' => 'Currency Rates'])

@section('content')
<section class="card section wide">
    <div class="section-head">
        <h2>Currency Rates</h2>
        @if($can('admin', 'finance'))
        <a class="button ghost" href="{{ route('currency-rates.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>
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
            <div class="text-sm text-slate-500">Menampilkan {{ $rates->firstItem() }}–{{ $rates->lastItem() }} dari {{ $rates->total() }}</div>
            {{ $rates->links() }}
        </div>
    @endif
</section>
@endsection
