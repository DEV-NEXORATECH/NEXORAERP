@extends('layouts.erp', ['activePage' => 'sales-commissions.edit-page', 'pageTitle' => 'Edit Komisi'])

@section('content')
<section class="section">
    <div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
        <div class="mb-8 border-b border-[#d7e3ef] pb-5">
            <h2 class="mb-1">Edit Komisi</h2>
            <p class="muted">Perbarui komisi sales.</p>
        </div>
        <form method="post" action="{{ route('sales-commissions.update', $commission) }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
            @csrf @method('put')
            <div class="grid gap-1.5"><label>User</label>
                <select name="user_id">
                    <option value="">—</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @selected($commission->user_id === $user->id)>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid gap-1.5"><label>Period</label><input name="period" value="{{ $commission->period }}" placeholder="2026-06" required></div>
            <div class="grid gap-1.5"><label>Base Amount (Rp)</label><input name="base_amount" type="number" min="0" value="{{ $commission->base_amount }}" required></div>
            <div class="grid gap-1.5"><label>Rate (%)</label><input name="rate" type="number" min="0" max="100" step="0.01" value="{{ $commission->rate }}" required></div>
            <div class="grid gap-1.5"><label>Status</label>
                <select name="status">
                    <option value="draft" @selected($commission->status === 'draft')>Draft</option>
                    <option value="approved" @selected($commission->status === 'approved')>Approved</option>
                    <option value="paid" @selected($commission->status === 'paid')>Paid</option>
                </select>
            </div>
            <div class="mt-4 flex items-center gap-3 md:col-span-2">
                <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Update Komisi
                </button>
                <a class="button ghost" href="{{ route('sales-commissions.index') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali
                </a>
            </div>
        </form>
    </div>
</section>
@endsection
