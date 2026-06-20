@extends('layouts.erp', ['activePage' => 'performance-reviews', 'pageTitle' => 'Performance Reviews'])

@section('content')
<section class="card section wide">
    <div class="section-head">
        <h2>Performance Reviews</h2>
        @if($can('admin', 'hr'))
        <a class="button ghost" href="{{ route('performance-reviews.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>
    <table>
        <thead><tr><th>Karyawan</th><th>Period</th><th>KPI</th><th>OKR</th><th>Rating</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($reviews as $row)
                <tr>
                    <td class="font-bold">{{ $row->employee->name }}</td>
                    <td>{{ $row->period }}</td>
                    <td>{{ $row->kpi_score }}</td>
                    <td>{{ $row->okr_score }}</td>
                    <td><span class="badge badge-{{ $row->rating }}">{{ str_replace('_', ' ', $row->rating) }}</span></td>
                    <td class="actions">
                        @if($can('admin', 'hr'))<a class="button mini ghost" href="{{ route('performance-reviews.edit-page', $row) }}">Edit</a>@endif
                        @if($can('admin'))<form method="post" action="{{ route('performance-reviews.destroy', $row) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="py-8 text-center text-slate-500">Belum ada data review.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($reviews->hasPages())
        <div class="pager">
            <div class="text-sm text-slate-500">Menampilkan {{ $reviews->firstItem() }}–{{ $reviews->lastItem() }} dari {{ $reviews->total() }}</div>
            {{ $reviews->links() }}
        </div>
    @endif
</section>
@endsection
