@extends('layouts.erp', ['activePage' => 'employee-edit', 'pageTitle' => 'Edit Karyawan'])

@section('content')
<div class="max-w-4xl mx-auto space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Edit Data Karyawan</h1>
            <p class="text-xs text-gray-500">Lengkapi formulir di bawah ini dengan data yang valid</p>
        </div>
        <a href="{{ route('hr.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
        <form method="post" action="{{ route('employees.update', $employee) }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2" class="space-y-4">
            
            @csrf @method('put')
            <div class="grid gap-1.5"><label>Nama</label><input name="name" value="{{ $employee->name }}" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900"></div>
            <div class="grid gap-1.5"><label>Posisi</label><select name="job_position_id" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900"><option value="">Manual</option>@foreach($jobPositions as $position)<option value="{{ $position->id }}" @selected($employee->job_position_id===$position->id)>{{ $position->name }}</option>@endforeach</select></div>
            <div class="grid gap-1.5"><label>Posisi Manual</label><input name="position" value="{{ $employee->position }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900"></div>
            <div class="grid gap-1.5"><label>Department</label><select name="department_id" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900"><option value="">Manual</option>@foreach($departments as $department)<option value="{{ $department->id }}" @selected($employee->department_id===$department->id)>{{ $department->name }}</option>@endforeach</select></div>
            <div class="grid gap-1.5"><label>Department Manual</label><input name="department" value="{{ $employee->department }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900"></div>
            <div class="grid gap-1.5"><label>Base Salary</label><input name="base_salary" type="number" min="0" value="{{ $employee->base_salary }}" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900"></div>
            <div class="mt-4 flex items-center gap-3 md:col-span-2">
                <button class="btn">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Update Karyawan
                </button>
                <a class="button ghost" href="{{ route('hr.index') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali
                </a>
            </div>
        
            <div class="pt-4 border-t border-gray-200 flex items-center justify-end gap-2">
                <a href="{{ route('hr.index') }}" class="px-3.5 py-2 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Batal</a>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition shadow-sm">Update Karyawan</button>
            </div>
        </form>
    </div>
</div>
@endsection
