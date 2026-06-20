@extends('layouts.erp', ['activePage' => 'attendance-edit', 'pageTitle' => 'Edit Attendance'])

@section('content')
<section class="section">
    <div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
        <div class="mb-8 border-b border-[#d7e3ef] pb-5">
            <h2 class="mb-1">Edit Attendance</h2>
            <p class="muted">Perbarui data kehadiran.</p>
        </div>
        <form method="post" action="{{ route('attendances.update', $attendance) }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
            @csrf @method('put')
            <div class="grid gap-1.5"><label>Karyawan</label>
                <select name="employee_id" required>
                    @foreach($employees as $e)<option value="{{ $e->id }}" @selected($attendance->employee_id === $e->id)>{{ $e->name }}</option>@endforeach
                </select>
            </div>
            <div class="grid gap-1.5"><label>Tanggal</label><input name="work_date" type="date" value="{{ $attendance->work_date }}" required></div>
            <div class="grid gap-1.5"><label>Check In</label><input name="check_in" type="time" value="{{ $attendance->check_in }}"></div>
            <div class="grid gap-1.5"><label>Check Out</label><input name="check_out" type="time" value="{{ $attendance->check_out }}"></div>
            <div class="grid gap-1.5"><label>Work Mode</label>
                <select name="work_mode" required>
                    <option value="office" @selected($attendance->work_mode === 'office')>Office</option>
                    <option value="remote" @selected($attendance->work_mode === 'remote')>Remote</option>
                    <option value="hybrid" @selected($attendance->work_mode === 'hybrid')>Hybrid</option>
                </select>
            </div>
            <div class="grid gap-1.5"><label>Status</label>
                <select name="status" required>
                    <option value="present" @selected($attendance->status === 'present')>Present</option>
                    <option value="late" @selected($attendance->status === 'late')>Late</option>
                    <option value="absent" @selected($attendance->status === 'absent')>Absent</option>
                    <option value="leave" @selected($attendance->status === 'leave')>Leave</option>
                </select>
            </div>
            <div class="grid gap-1.5 md:col-span-2"><label>Notes</label><textarea name="notes" rows="3">{{ $attendance->notes }}</textarea></div>
            <div class="mt-4 flex items-center gap-3 md:col-span-2">
                <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Update Attendance
                </button>
                <a class="button ghost" href="{{ route('attendances.index') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali
                </a>
            </div>
        </form>
    </div>
</section>
@endsection
