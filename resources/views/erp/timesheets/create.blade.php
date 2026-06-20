@extends('layouts.erp', ['activePage' => 'timesheet-create', 'pageTitle' => 'Tambah Timesheet'])

@section('content')
<section class="section">
    <div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
        <div class="mb-8 border-b border-[#d7e3ef] pb-5">
            <h2 class="mb-1">Tambah Timesheet</h2>
            <p class="muted">Catat jam kerja karyawan.</p>
        </div>
        <form method="post" action="{{ route('timesheets.store') }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
            @csrf
            <div class="grid gap-1.5"><label>Karyawan</label>
                <select name="employee_id" required>
                    @foreach($employees as $e)<option value="{{ $e->id }}">{{ $e->name }}</option>@endforeach
                </select>
            </div>
            <div class="grid gap-1.5"><label>Project</label>
                <select name="project_id">
                    <option value="">-- Tanpa Project --</option>
                    @foreach($projects as $p)<option value="{{ $p->id }}">{{ $p->code }} - {{ $p->name }}</option>@endforeach
                </select>
            </div>
            <div class="grid gap-1.5"><label>Tanggal</label><input name="work_date" type="date" required></div>
            <div class="grid gap-1.5"><label>Jam Kerja</label><input name="hours" type="number" step="0.5" min="0" max="24" required></div>
            <div class="grid gap-1.5"><label>Status</label>
                <select name="status" required>
                    <option value="submitted">Submitted</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            <div class="grid gap-1.5 md:col-span-2"><label>Deskripsi</label><textarea name="description" rows="3"></textarea></div>
            <div class="mt-4 flex items-center gap-3 md:col-span-2">
                <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Simpan Timesheet
                </button>
                <a class="button ghost" href="{{ route('timesheets.index') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali
                </a>
            </div>
        </form>
    </div>
</section>
@endsection
