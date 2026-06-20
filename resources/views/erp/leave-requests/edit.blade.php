@extends('layouts.erp', ['activePage' => 'leave-request-edit', 'pageTitle' => 'Edit Leave'])

@section('content')
<section class="section">
    <div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
        <div class="mb-8 border-b border-[#d7e3ef] pb-5">
            <h2 class="mb-1">Edit Leave Request</h2>
            <p class="muted">Perbarui data cuti / izin.</p>
        </div>
        <form method="post" action="{{ route('leave-requests.update', $leave) }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
            @csrf @method('put')
            <div class="grid gap-1.5"><label>Karyawan</label>
                <select name="employee_id" required>
                    @foreach($employees as $e)<option value="{{ $e->id }}" @selected($leave->employee_id === $e->id)>{{ $e->name }}</option>@endforeach
                </select>
            </div>
            <div class="grid gap-1.5"><label>Tipe Cuti</label>
                <select name="type" required>
                    <option value="annual" @selected($leave->type === 'annual')>Annual</option>
                    <option value="sick" @selected($leave->type === 'sick')>Sick</option>
                    <option value="unpaid" @selected($leave->type === 'unpaid')>Unpaid</option>
                    <option value="special" @selected($leave->type === 'special')>Special</option>
                </select>
            </div>
            <div class="grid gap-1.5"><label>Tanggal Mulai</label><input name="start_date" type="date" value="{{ $leave->start_date }}" required></div>
            <div class="grid gap-1.5"><label>Tanggal Selesai</label><input name="end_date" type="date" value="{{ $leave->end_date }}" required></div>
            <div class="grid gap-1.5"><label>Status</label>
                <select name="status" required>
                    <option value="pending" @selected($leave->status === 'pending')>Pending</option>
                    <option value="approved" @selected($leave->status === 'approved')>Approved</option>
                    <option value="rejected" @selected($leave->status === 'rejected')>Rejected</option>
                </select>
            </div>
            <div class="grid gap-1.5 md:col-span-2"><label>Alasan</label><textarea name="reason" rows="3">{{ $leave->reason }}</textarea></div>
            <div class="mt-4 flex items-center gap-3 md:col-span-2">
                <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Update Leave
                </button>
                <a class="button ghost" href="{{ route('leave-requests.index') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali
                </a>
            </div>
        </form>
    </div>
</section>
@endsection
