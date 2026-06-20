@extends('layouts.erp', ['activePage' => 'revenue-schedules.index', 'pageTitle' => 'Revenue Schedules'])

@section('content')
<section class="card section wide">
    <div class="section-head">
        <h2>Revenue Schedules</h2>
        @if($can('admin', 'finance'))
        <a class="button ghost" href="{{ route('revenue-schedules.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>
    <table>
        <thead>
            <tr><th>Project</th><th>Tanggal</th><th>Amount</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($schedules as $row)
                <tr>
                    <td class="font-bold">{{ $row->project?->name ?? '-' }}<br><span class="muted">{{ $row->project?->code ?? '' }}</span></td>
                    <td>{{ $row->schedule_date }}</td>
                    <td>{{ $rp($row->amount) }}</td>
                    <td><span class="badge badge-{{ $row->status === 'recognized' ? 'active' : ($row->status === 'cancelled' ? 'void' : 'pending') }}">{{ $row->status }}</span></td>
                    <td class="actions">
                        @if($can('admin', 'finance'))<a class="button mini ghost" href="{{ route('revenue-schedules.edit-page', $row) }}">Edit</a>@endif
                        @if($can('admin', 'finance'))<form method="post" action="{{ route('revenue-schedules.destroy', $row) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="py-8 text-center text-slate-500">Belum ada revenue schedule.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($schedules->hasPages())
        <div class="pager">
            <div class="text-sm text-slate-500">Menampilkan {{ $schedules->firstItem() }}–{{ $schedules->lastItem() }} dari {{ $schedules->total() }}</div>
            {{ $schedules->links() }}
        </div>
    @endif
</section>
@endsection
