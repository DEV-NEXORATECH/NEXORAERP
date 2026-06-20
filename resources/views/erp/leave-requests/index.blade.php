@extends('layouts.erp', ['activePage' => 'leave-requests', 'pageTitle' => 'Leave Requests'])

@section('content')
<section class="card section wide">
    <div class="section-head">
        <h2>Leave Requests</h2>
        @if($can('admin', 'hr'))
        <a class="button ghost" href="{{ route('leave-requests.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>
    <table>
        <thead><tr><th>Karyawan</th><th>Tipe</th><th>Mulai</th><th>Selesai</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($leaves as $row)
                <tr>
                    <td class="font-bold">{{ $row->employee->name }}</td>
                    <td><span class="badge badge-{{ $row->type }}">{{ $row->type }}</span></td>
                    <td>{{ $row->start_date }}</td>
                    <td>{{ $row->end_date }}</td>
                    <td><span class="badge badge-{{ $row->status }}">{{ $row->status }}</span></td>
                    <td class="actions">
                        @if($can('admin', 'hr'))<a class="button mini ghost" href="{{ route('leave-requests.edit-page', $row) }}">Edit</a>@endif
                        @if($can('admin'))<form method="post" action="{{ route('leave-requests.destroy', $row) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="py-8 text-center text-slate-500">Belum ada data leave.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($leaves->hasPages())
        <div class="pager">
            <div class="text-sm text-slate-500">Menampilkan {{ $leaves->firstItem() }}–{{ $leaves->lastItem() }} dari {{ $leaves->total() }}</div>
            {{ $leaves->links() }}
        </div>
    @endif
</section>
@endsection
