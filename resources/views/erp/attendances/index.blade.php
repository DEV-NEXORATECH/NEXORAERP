@extends('layouts.erp', ['activePage' => 'attendances', 'pageTitle' => 'Attendances'])

@section('content')
<section class="card section wide">
    <div class="section-head">
        <h2>Attendances</h2>
        @if($can('admin', 'hr'))
        <a class="button ghost" href="{{ route('attendances.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>
    <table>
        <thead><tr><th>Karyawan</th><th>Tanggal</th><th>Masuk</th><th>Keluar</th><th>Mode</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($attendances as $row)
                <tr>
                    <td class="font-bold">{{ $row->employee->name }}</td>
                    <td>{{ $row->work_date }}</td>
                    <td>{{ $row->check_in ?? '-' }}</td>
                    <td>{{ $row->check_out ?? '-' }}</td>
                    <td><span class="badge badge-{{ $row->work_mode }}">{{ $row->work_mode }}</span></td>
                    <td><span class="badge badge-{{ $row->status }}">{{ $row->status }}</span></td>
                    <td class="actions">
                        @if($can('admin', 'hr'))<a class="button mini ghost" href="{{ route('attendances.edit-page', $row) }}">Edit</a>@endif
                        @if($can('admin'))<form method="post" action="{{ route('attendances.destroy', $row) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="py-8 text-center text-slate-500">Belum ada data attendance.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($attendances->hasPages())
        <div class="pager">
            <div class="text-sm text-slate-500">Menampilkan {{ $attendances->firstItem() }}–{{ $attendances->lastItem() }} dari {{ $attendances->total() }}</div>
            {{ $attendances->links() }}
        </div>
    @endif
</section>
@endsection
