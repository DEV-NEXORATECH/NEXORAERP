@extends('layouts.erp', ['activePage' => 'timesheets', 'pageTitle' => 'Timesheets'])

@section('content')
<section class="card section wide">
    <div class="section-head">
        <h2>Timesheets</h2>
        @if($can('admin', 'hr'))
        <a class="button ghost" href="{{ route('timesheets.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>
    <table>
        <thead><tr><th>Karyawan</th><th>Project</th><th>Tanggal</th><th>Jam</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($timesheets as $row)
                <tr>
                    <td class="font-bold">{{ $row->employee->name }}</td>
                    <td class="muted">{{ $row->project?->code ?? '-' }}</td>
                    <td>{{ $row->work_date }}</td>
                    <td>{{ $row->hours }}h</td>
                    <td><span class="badge badge-{{ $row->status }}">{{ $row->status }}</span></td>
                    <td class="actions">
                        @if($can('admin', 'hr'))<a class="button mini ghost" href="{{ route('timesheets.edit-page', $row) }}">Edit</a>@endif
                        @if($can('admin'))<form method="post" action="{{ route('timesheets.destroy', $row) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="py-8 text-center text-slate-500">Belum ada data timesheet.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($timesheets->hasPages())
        <div class="pager">
            <div class="text-sm text-slate-500">Menampilkan {{ $timesheets->firstItem() }}–{{ $timesheets->lastItem() }} dari {{ $timesheets->total() }}</div>
            {{ $timesheets->links() }}
        </div>
    @endif
</section>
@endsection
