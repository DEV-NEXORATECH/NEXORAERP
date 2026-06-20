@extends('layouts.erp', ['activePage' => 'client-contracts.create-page', 'pageTitle' => 'Tambah Contract'])

@section('content')
<section class="section">
    <div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
        <div class="mb-8 border-b border-[#d7e3ef] pb-5">
            <h2 class="mb-1">Tambah Contract</h2>
            <p class="muted">Catat kontrak klien baru.</p>
        </div>
        <form method="post" action="{{ route('client-contracts.store') }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
            @csrf
            <div class="grid gap-1.5"><label>Title</label><input name="title" required></div>
            <div class="grid gap-1.5"><label>Amount (Rp)</label><input name="amount" type="number" min="0" required></div>
            <div class="grid gap-1.5"><label>Start Date</label><input name="start_date" type="date"></div>
            <div class="grid gap-1.5"><label>End Date</label><input name="end_date" type="date"></div>
            <div class="grid gap-1.5"><label>Reminder Date</label><input name="reminder_date" type="date"></div>
            <div class="grid gap-1.5"><label>Status</label>
                <select name="status">
                    <option value="draft">Draft</option>
                    <option value="active">Active</option>
                    <option value="expired">Expired</option>
                    <option value="terminated">Terminated</option>
                </select>
            </div>
            <div class="grid gap-1.5"><label>Client</label>
                <select name="client_id">
                    <option value="">—</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid gap-1.5 md:col-span-2"><label>Notes</label><textarea name="notes" rows="3"></textarea></div>
            <div class="mt-4 flex items-center gap-3 md:col-span-2">
                <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Simpan Contract
                </button>
                <a class="button ghost" href="{{ route('client-contracts.index') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali
                </a>
            </div>
        </form>
    </div>
</section>
@endsection
