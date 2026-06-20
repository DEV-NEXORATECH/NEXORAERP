@extends('layouts.erp', ['activePage' => 'client-contracts.edit-page', 'pageTitle' => 'Edit Contract'])

@section('content')
<section class="section">
    <div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
        <div class="mb-8 border-b border-[#d7e3ef] pb-5">
            <h2 class="mb-1">Edit Contract</h2>
            <p class="muted">Perbarui data kontrak klien.</p>
        </div>
        <form method="post" action="{{ route('client-contracts.update', $contract) }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
            @csrf @method('put')
            <div class="grid gap-1.5"><label>Title</label><input name="title" value="{{ $contract->title }}" required></div>
            <div class="grid gap-1.5"><label>Amount (Rp)</label><input name="amount" type="number" min="0" value="{{ $contract->amount }}" required></div>
            <div class="grid gap-1.5"><label>Start Date</label><input name="start_date" type="date" value="{{ $contract->start_date ? \Carbon\Carbon::parse($contract->start_date)->format('Y-m-d') : '' }}"></div>
            <div class="grid gap-1.5"><label>End Date</label><input name="end_date" type="date" value="{{ $contract->end_date ? \Carbon\Carbon::parse($contract->end_date)->format('Y-m-d') : '' }}"></div>
            <div class="grid gap-1.5"><label>Reminder Date</label><input name="reminder_date" type="date" value="{{ $contract->reminder_date ? \Carbon\Carbon::parse($contract->reminder_date)->format('Y-m-d') : '' }}"></div>
            <div class="grid gap-1.5"><label>Status</label>
                <select name="status">
                    <option value="draft" @selected($contract->status === 'draft')>Draft</option>
                    <option value="active" @selected($contract->status === 'active')>Active</option>
                    <option value="expired" @selected($contract->status === 'expired')>Expired</option>
                    <option value="terminated" @selected($contract->status === 'terminated')>Terminated</option>
                </select>
            </div>
            <div class="grid gap-1.5"><label>Client</label>
                <select name="client_id">
                    <option value="">—</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" @selected($contract->client_id === $client->id)>{{ $client->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid gap-1.5 md:col-span-2"><label>Notes</label><textarea name="notes" rows="3">{{ $contract->notes }}</textarea></div>
            <div class="mt-4 flex items-center gap-3 md:col-span-2">
                <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Update Contract
                </button>
                <a class="button ghost" href="{{ route('client-contracts.index') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali
                </a>
            </div>
        </form>
    </div>
</section>
@endsection
