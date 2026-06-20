@extends('layouts.erp', ['activePage' => 'purchase-requisition-edit', 'pageTitle' => 'Edit Purchase Requisition'])

@section('content')
<section class="section">
    <div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
        <div class="mb-8 border-b border-[#d7e3ef] pb-5">
            <h2 class="mb-1">Edit Purchase Requisition</h2>
            <p class="muted">Perbarui data purchase requisition.</p>
        </div>
        <form method="post" action="{{ route('purchase-requisitions.update', $requisition) }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
            @csrf @method('put')
            <div class="grid gap-1.5"><label>Pemohon</label>
                <select name="requester_id">
                    <option value="">System</option>
                    @foreach($users as $user)<option value="{{ $user->id }}" @selected($requisition->requester_id === $user->id)>{{ $user->name }}</option>@endforeach
                </select>
            </div>
            <div class="grid gap-1.5"><label>Department</label>
                <select name="department_id">
                    <option value="">General</option>
                    @foreach($departments as $department)<option value="{{ $department->id }}" @selected($requisition->department_id === $department->id)>{{ $department->name }}</option>@endforeach
                </select>
            </div>
            <div class="grid gap-1.5"><label>Judul</label><input name="title" value="{{ $requisition->title }}" required></div>
            <div class="grid gap-1.5"><label>Amount</label><input name="amount" type="number" min="0" value="{{ $requisition->amount }}" required></div>
            <div class="grid gap-1.5"><label>Tanggal Dibutuhkan</label><input name="required_date" type="date" value="{{ $requisition->required_date?->format('Y-m-d') }}"></div>
            <div class="grid gap-1.5"><label>Status</label>
                <select name="status">
                    <option value="draft" @selected($requisition->status === 'draft')>Draft</option>
                    <option value="submitted" @selected($requisition->status === 'submitted')>Submitted</option>
                    <option value="approved" @selected($requisition->status === 'approved')>Approved</option>
                    <option value="rejected" @selected($requisition->status === 'rejected')>Rejected</option>
                </select>
            </div>
            <div class="grid gap-1.5 md:col-span-2"><label>Alasan / Catatan</label><textarea name="reason" rows="3">{{ $requisition->reason }}</textarea></div>
            <div class="mt-4 flex items-center gap-3 md:col-span-2">
                <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Update PR
                </button>
                <a class="button ghost" href="{{ route('purchase-requisitions.index') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali
                </a>
            </div>
        </form>
    </div>
</section>
@endsection
