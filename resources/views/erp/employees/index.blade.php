@extends('layouts.erp', ['activePage' => 'employees', 'pageTitle' => 'Employee List'])

@section('content')
<section class="card section wide">
    <div class="section-head">
        <h2>Employee List</h2>
        @if($can('admin', 'hr'))
        <a class="button ghost" href="{{ route('employees.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>
    <form method="get" action="{{ route('hr.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 placeholder:text-slate-400 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <select name="department_id" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
            <option value="">Semua Department</option>
            @foreach($departments as $department)
                <option value="{{ $department->id }}" @selected(request('department_id')==$department->id)>{{ $department->name }}</option>
            @endforeach
        </select>
        <select name="job_position_id" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
            <option value="">Semua Posisi</option>
            @foreach($jobPositions as $position)
                <option value="{{ $position->id }}" @selected(request('job_position_id')==$position->id)>{{ $position->name }}</option>
            @endforeach
        </select>
        <div class="flex gap-2 items-end">
            <button class="rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#003d78]">Filter</button>
            <a href="{{ route('hr.index') }}" class="rounded-xl border border-[#d7e3ef] bg-white px-5 py-2.5 text-sm font-bold text-[#0059A7] hover:bg-[#f3f8fc]">Reset</a>
        </div>
    </form>
    <table>
        <thead>
            <tr><th>Nama</th><th>Posisi</th><th>Department</th><th>Base Salary</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($employeesPage as $employee)
                <tr>
                    <td class="font-bold">{{ $employee->name }}</td>
                    <td>{{ $employee->position }}</td>
                    <td>{{ $employee->department }}</td>
                    <td class="font-bold">{{ $rp($employee->base_salary) }}</td>
                    <td class="actions">
                        @if($can('admin', 'hr'))<a class="button mini ghost" href="{{ route('employees.edit-page', $employee) }}">Edit</a>@endif
                        @if($can('admin'))<form method="post" action="{{ route('employees.destroy', $employee) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="py-8 text-center text-slate-500">Belum ada karyawan.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($employeesPage->hasPages() || $employeesPage->total() > 0)
        <div class="pager">
            <span>Menampilkan {{ $employeesPage->firstItem() }}-{{ $employeesPage->lastItem() }} dari {{ $employeesPage->total() }}</span>
            {{ $employeesPage->links() }}
        </div>
    @endif
</section>
@endsection
