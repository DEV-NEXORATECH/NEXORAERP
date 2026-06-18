@php $statusOptions = ['proposal' => ['draft', 'sent', 'approved', 'rejected'], 'salary' => ['draft', 'approved', 'paid'], 'reimbursement' => ['pending', 'approved', 'paid', 'rejected'], 'invoice' => ['draft', 'sent', 'partial', 'paid', 'void']]; @endphp
@extends('layouts.erp', ['activePage' => 'invoice-create', 'pageTitle' => 'Buat Invoice'])

@section('content')
<section class="section">
    <div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
        <div class="mb-8 border-b border-[#d7e3ef] pb-5">
            <h2 class="mb-1">Buat Invoice</h2>
            <p class="muted">Buat invoice baru untuk project.</p>
        </div>
        <form method="post" action="{{ route('invoices.store') }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
            @csrf
            <div class="grid gap-1.5"><label>Project</label><select name="project_id" required>@foreach($projects as $project)<option value="{{ $project->id }}">{{ $project->code }}</option>@endforeach</select></div>
            <div class="grid gap-1.5"><label>Proposal Approved</label><select name="proposal_id"><option value="">Tanpa proposal</option>@foreach($proposals->where('status','approved') as $proposal)<option value="{{ $proposal->id }}">{{ $proposal->title }}</option>@endforeach</select></div>
            <div class="grid gap-1.5"><label>No Invoice</label><input name="number" placeholder="Auto jika kosong"></div>
            <div class="grid gap-1.5"><label>Status</label><select name="status">@foreach($statusOptions['invoice'] as $status)<option>{{ $status }}</option>@endforeach</select></div>
            <div class="grid gap-1.5"><label>Issue</label><input name="issue_date" type="date" value="{{ now()->toDateString() }}" required></div>
            <div class="grid gap-1.5"><label>Due</label><input name="due_date" type="date"></div>
            <div class="grid gap-1.5"><label>Amount</label><input name="amount" type="number" min="0" required></div>
            <div class="grid gap-1.5"><label>Tax %</label><input name="tax_rate" type="number" min="0" value="0"></div>
            <div class="grid gap-1.5"><label>Notes</label><input name="notes"></div>
            <div class="grid gap-1.5 md:col-span-2"><label>Payment Terms</label><textarea name="payment_terms"></textarea></div>
            <div class="mt-4 flex items-center gap-3 md:col-span-2">
                <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Simpan Invoice
                </button>
                <a class="button ghost" href="{{ route('finance.index') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali
                </a>
            </div>
        </form>
    </div>
</section>
@endsection
