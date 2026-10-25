@extends('layouts.erp', ['activePage' => 'departments', 'pageTitle' => 'Departments'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#0B2345]/5" id="departments">
    <div class="section-head">
        <h2>Daftar Department</h2>
        @if($can('admin', 'finance') || $can('admin'))
        <a href="{{ route('departments.create-page') }}" class="button ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>

    <form method="get" action="{{ route('departments.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="field-input">
        <div class="flex gap-2 items-end">
            <button class="filter-btn">Filter</button>
            <a href="{{ route('departments.index') }}" class="btn-outline">Reset</a>
        </div>
    </form>

    <table>
        <thead><tr><th>Nama</th><th>Aksi</th></tr></thead>
        <tbody>
            @foreach($departments as $department)
            <tr>
                <td class="font-bold">{{ $department->name }}</td>
                <td class="actions">
                    <a href="{{ route('departments.edit-page', $department) }}" class="mini ghost">Edit</a>
                    <form method="post" action="{{ route('departments.destroy', $department) }}" class="inline">@csrf @method('delete')<button class="mini danger">Delete</button></form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($departments->hasPages())
    <div class="pager">
        <span>Menampilkan {{ $departments->firstItem() }}-{{ $departments->lastItem() }} dari {{ $departments->total() }}</span>
        {{ $departments->links() }}
    </div>
    @endif
</section>
@endsection
