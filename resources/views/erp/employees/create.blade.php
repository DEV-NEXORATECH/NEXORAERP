@extends('layouts.erp', ['activePage' => 'employee-create', 'pageTitle' => 'Tambah Karyawan'])

@section('content')
<section class="section">
    <div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
        <div class="mb-8 border-b border-[#d7e3ef] pb-5">
            <h2 class="mb-1">Tambah Karyawan</h2>
            <p class="muted">Daftarkan karyawan baru.</p>
        </div>
        <form method="post" action="{{ route('employees.store') }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
            @csrf
            <div class="grid gap-1.5"><label>Nama</label><input name="name" required></div>
            <div class="grid gap-1.5"><label>Posisi</label><select name="job_position_id"><option value="">Manual</option>@foreach($jobPositions as $position)<option value="{{ $position->id }}">{{ $position->name }}</option>@endforeach</select></div>
            <div class="grid gap-1.5"><label>Posisi Manual</label><input name="position"></div>
            <div class="grid gap-1.5"><label>Department</label><select name="department_id"><option value="">Manual</option>@foreach($departments as $department)<option value="{{ $department->id }}">{{ $department->name }}</option>@endforeach</select></div>
            <div class="grid gap-1.5"><label>Department Manual</label><input name="department" value="IT"></div>
            <div class="grid gap-1.5"><label>Base Salary</label><input name="base_salary" type="number" min="0" required></div>
            <div class="mt-4 flex items-center gap-3 md:col-span-2">
                <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Simpan Karyawan
                </button>
                <a class="button ghost" href="{{ route('hr.index') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali
                </a>
            </div>
        </form>
    </div>
</section>
@endsection
