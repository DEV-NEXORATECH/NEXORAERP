@extends('layouts.erp', ['activePage' => 'employee-skills', 'pageTitle' => 'Employee Skills'])

@section('content')
<div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
    <div class="section-head">
        <h2>Daftar Employee Skill</h2>
        @if($can('admin', 'hr'))
        <a class="button ghost" href="{{ route('employee-skills.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>
    <form method="get" action="{{ route('employee-skills.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 placeholder:text-slate-400 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <select name="employee_id" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
            <option value="">Semua Karyawan</option>
            @foreach($employees as $emp)
                <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
            @endforeach
        </select>
        <select name="level" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
            <option value="">Semua Level</option>
            <option value="basic" {{ request('level') === 'basic' ? 'selected' : '' }}>Basic</option>
            <option value="intermediate" {{ request('level') === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
            <option value="advanced" {{ request('level') === 'advanced' ? 'selected' : '' }}>Advanced</option>
            <option value="expert" {{ request('level') === 'expert' ? 'selected' : '' }}>Expert</option>
        </select>
        <div class="flex gap-2 items-end">
            <button class="rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#003d78]">Filter</button>
            <a href="{{ route('employee-skills.index') }}" class="rounded-xl border border-[#d7e3ef] bg-white px-5 py-2.5 text-sm font-bold text-[#0059A7] hover:bg-[#f3f8fc]">Reset</a>
        </div>
    </form>
    <table>
        <thead><tr><th>Karyawan</th><th>Skill</th><th>Level</th><th>Notes</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($items as $row)
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
    @if($items->hasPages())
    <div class="pager">
        <span>Menampilkan {{ $items->firstItem() }}-{{ $items->lastItem() }} dari {{ $items->total() }}</span>
        {{ $items->links() }}
    </div>
    @endif
</div>
@endsection
