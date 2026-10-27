@extends('layouts.erp', ['activePage' => 'attendance-edit', 'pageTitle' => 'Edit Attendance'])

@section('content')
<div class="max-w-4xl mx-auto space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-[#0F172A] tracking-tight">Edit Presensi Kehadiran</h1>
            <p class="text-xs text-[#475569]">Lengkapi formulir di bawah ini dengan data yang valid</p>
        </div>
        <a href="{{ route('attendances.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-[#475569] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F8FAFC] transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg border border-[#D1D5DB] p-6 shadow-sm">
        <form method="post" action="{{ route('attendances.update', $attendance) }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2" class="space-y-4">
            
            @csrf @method('put')
            <div class="grid gap-1.5"><label>Karyawan</label>
                <select name="employee_id" required class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]">
                    @foreach($employees as $e)<option value="{{ $e->id }}" @selected($attendance->employee_id === $e->id)>{{ $e->name }}</option>@endforeach
                </select>
            </div>
            <div class="grid gap-1.5"><label>Tanggal</label><input name="work_date" type="date" value="{{ $attendance->work_date }}" required class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]"></div>
            <div class="grid gap-1.5"><label>Check In</label><input name="check_in" type="time" value="{{ $attendance->check_in }}" class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]"></div>
            <div class="grid gap-1.5"><label>Check Out</label><input name="check_out" type="time" value="{{ $attendance->check_out }}" class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]"></div>
            <div class="grid gap-1.5"><label>Work Mode</label>
                <select name="work_mode" required class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]">
                    <option value="office" @selected($attendance->work_mode === 'office')>Office</option>
                    <option value="remote" @selected($attendance->work_mode === 'remote')>Remote</option>
                    <option value="hybrid" @selected($attendance->work_mode === 'hybrid')>Hybrid</option>
                </select>
            </div>
            <div class="grid gap-1.5"><label>Status</label>
                <select name="status" required class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]">
                    <option value="present" @selected($attendance->status === 'present')>Present</option>
                    <option value="late" @selected($attendance->status === 'late')>Late</option>
                    <option value="absent" @selected($attendance->status === 'absent')>Absent</option>
                    <option value="leave" @selected($attendance->status === 'leave')>Leave</option>
                </select>
            </div>
            <div class="grid gap-1.5 md:col-span-2"><label>Notes</label><textarea name="notes" rows="3" class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]">{{ $attendance->notes }}</textarea></div>
            <div class="mt-4 flex items-center gap-3 md:col-span-2">
                <button class="btn">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Update Attendance
                </button>
                <a class="button ghost" href="{{ route('attendances.index') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali
                </a>
            </div>
        
            <div class="pt-4 border-t border-[#D1D5DB] flex items-center justify-end gap-2">
                <a href="{{ route('attendances.index') }}" class="px-3.5 py-2 text-xs font-medium text-[#475569] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F8FAFC] transition">Batal</a>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition shadow-sm">Update Attendance</button>
            </div>
        </form>
    </div>
</div>
@endsection
