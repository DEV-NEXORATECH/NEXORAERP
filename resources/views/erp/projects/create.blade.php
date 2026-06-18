@extends('layouts.erp', ['activePage' => 'project-create', 'pageTitle' => 'Tambah Project'])

@section('content')
<section class="section">
    <div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
        <div class="mb-8 border-b border-[#d7e3ef] pb-5">
            <h2 class="mb-1">Tambah Project</h2>
            <p class="muted">Buat project baru untuk klien.</p>
        </div>
        <form method="post" action="{{ route('projects.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
            @csrf
            <div class="grid gap-1.5"><label>Kode</label><input name="code" placeholder="Auto jika kosong"></div>
            <div class="grid gap-1.5"><label>Status</label><select name="status"><option>planning</option><option>active</option><option>done</option><option>hold</option></select></div>
            <div class="grid gap-1.5"><label>Nama Project</label><input name="name" required></div>
            <div class="grid gap-1.5"><label>Client</label><select name="client_id"><option value="">Manual</option>@foreach ($clients as $client)<option value="{{ $client->id }}">{{ $client->name }}</option>@endforeach</select></div>
            <div class="grid gap-1.5"><label>Client Manual</label><input name="client"></div>
            <div class="grid gap-1.5"><label>Budget</label><input name="budget" type="number" min="0" value="0"></div>
            <div class="grid gap-1.5"><label>Nilai Kontrak</label><input name="contract_value" type="number" min="0" value="0"></div>
            <div class="grid gap-1.5"><label>Mulai</label><input name="start_date" type="date"></div>
            <div class="grid gap-1.5"><label>Selesai</label><input name="end_date" type="date"></div>
            <div class="grid gap-1.5 md:col-span-2"><label>Upload Kontrak</label><input name="contract_file" type="file"></div>
            <div class="mt-4 flex items-center gap-3 md:col-span-2">
                <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Simpan Project
                </button>
                <a class="button ghost" href="{{ route('projects.index') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali
                </a>
            </div>
        </form>
    </div>
</section>
@endsection
