@extends('layouts.erp', ['activePage' => 'proposal-edit', 'pageTitle' => 'Edit Proposal'])

@section('content')
<section class="grid two section">
    <div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
        <div class="mb-8 border-b border-[#d7e3ef] pb-5">
            <h2 class="mb-1">Edit Proposal</h2>
            <p class="muted">Perbarui informasi proposal.</p>
        </div>
        <form method="post" action="{{ route('proposals.update', $proposal) }}" enctype="multipart/form-data" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
            @csrf @method('put')
            <div class="grid gap-1.5"><label>No Proposal</label><input name="number" value="{{ $proposal->number }}"></div>
            <div class="grid gap-1.5 md:col-span-2"><label>Project</label><select name="project_id" required>@foreach ($projects as $project)<option value="{{ $project->id }}" @selected($proposal->project_id===$project->id)>{{ $project->code }} - {{ $project->name }}</option>@endforeach</select></div>
            <div class="grid gap-1.5"><label>Judul</label><input name="title" value="{{ $proposal->title }}" required></div>
            <div class="grid gap-1.5"><label>Status</label><select name="status">@foreach($statusOptions['proposal'] as $status)<option @selected($proposal->status===$status)>{{ $status }}</option>@endforeach</select></div>
            <div class="grid gap-1.5"><label>Amount</label><input name="amount" type="number" min="0" value="{{ $proposal->amount }}" required></div>
            <div class="grid gap-1.5"><label>Valid Until</label><input name="valid_until" type="date" value="{{ $proposal->valid_until }}"></div>
            <div class="grid gap-1.5"><label>Upload Signed Baru</label><input name="signed_file" type="file"></div>
            <div class="grid gap-1.5 md:col-span-2"><label>Scope</label><textarea name="scope">{{ $proposal->scope }}</textarea></div>
            <div class="mt-4 flex items-center gap-3 md:col-span-2">
                <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Update Proposal
                </button>
                <a class="button ghost" href="{{ route('sales.index') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali
                </a>
            </div>
        </form>
    </div>
</section>
@endsection
