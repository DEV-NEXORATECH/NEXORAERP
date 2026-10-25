@extends('layouts.erp', ['activePage' => 'employees', 'pageTitle' => 'Employee List'])

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Data Karyawan (Employees)</h1>
            <p class="text-xs text-gray-500">Kelola master data dan transaksi Data Karyawan (Employees)</p>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
        <form method="get" action="{{ route('hr.index') }}" class="p-3 border-b border-gray-200 bg-gray-50/50 flex flex-wrap items-center gap-2.5">
            
        <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="px-2.5 py-1.5 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white">
        <select name="department_id" class="px-2.5 py-1.5 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white">
            <option value="">Semua Department</option>
            @foreach($departments as $department)
                <option value="{{ $department->id }}" @selected(request('department_id')==$department->id)>{{ $department->name }}</option>
            @endforeach
        </select>
        <select name="job_position_id" class="px-2.5 py-1.5 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white">
            <option value="">Semua Posisi</option>
            @foreach($jobPositions as $position)
                <option value="{{ $position->id }}" @selected(request('job_position_id')==$position->id)>{{ $position->name }}</option>
            @endforeach
        </select>
        <div class="flex gap-2 items-end">
            <button class="px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition">Filter</button>
            <a href="{{ route('hr.index') }}" class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Reset</a>
        </div>
    
        </form>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Nama</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Posisi</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Department</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Base Salary</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    
            @forelse($employeesPage as $employee)
                <tr>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $employee->name }}</td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $employee->position }}</td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $employee->department }}</td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $rp($employee->base_salary) }}</td>
                    <td class="px-3 py-2 text-xs text-right whitespace-nowrap">
                        @if($can('admin', 'hr'))<a class="button mini ghost" href="{{ route('employees.edit-page', $employee) }}">Edit</a>@endif
                        @if($can('admin'))<form method="post" action="{{ route('employees.destroy', $employee) }}">@csrf @method('delete')<button class="inline-flex items-center px-2 py-1 text-[11px] font-medium text-red-700 bg-red-50 border border-red-200 rounded hover:bg-red-100 transition">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="py-8 text-center text-slate-500">Belum ada karyawan.</td></tr>
            @endforelse
        
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
