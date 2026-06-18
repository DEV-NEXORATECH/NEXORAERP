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
    @if($employeesPage->hasPages())
        <div class="pager">
            <div class="text-sm text-slate-500">Menampilkan {{ $employeesPage->firstItem() }}–{{ $employeesPage->lastItem() }} dari {{ $employeesPage->total() }}</div>
            {{ $employeesPage->links() }}
        </div>
    @endif
</section>
@endsection
