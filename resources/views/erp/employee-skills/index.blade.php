@extends('layouts.erp', ['activePage' => 'employee-skills', 'pageTitle' => 'Employee Skills'])

@section('content')
<section class="card section wide">
    <div class="section-head">
        <h2>Employee Skills</h2>
        @if($can('admin', 'hr'))
        <a class="button ghost" href="{{ route('employee-skills.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>
    <table>
        <thead><tr><th>Karyawan</th><th>Skill</th><th>Level</th><th>Notes</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($skills as $row)
                <tr>
                    <td class="font-bold">{{ $row->employee->name }}</td>
                    <td>{{ $row->skill }}</td>
                    <td><span class="badge badge-{{ $row->level }}">{{ $row->level }}</span></td>
                    <td class="muted">{{ $row->notes ?? '-' }}</td>
                    <td class="actions">
                        @if($can('admin', 'hr'))<a class="button mini ghost" href="{{ route('employee-skills.edit-page', $row) }}">Edit</a>@endif
                        @if($can('admin'))<form method="post" action="{{ route('employee-skills.destroy', $row) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="py-8 text-center text-slate-500">Belum ada data skill.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($skills->hasPages())
        <div class="pager">
            <div class="text-sm text-slate-500">Menampilkan {{ $skills->firstItem() }}–{{ $skills->lastItem() }} dari {{ $skills->total() }}</div>
            {{ $skills->links() }}
        </div>
    @endif
</section>
@endsection
