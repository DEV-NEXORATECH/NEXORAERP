@extends('layouts.erp', ['activePage' => 'journal-entry-edit', 'pageTitle' => 'Edit Journal Entry'])

@section('content')
<section class="section">
    <div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
        <div class="mb-8 border-b border-[#d7e3ef] pb-5">
            <h2 class="mb-1">Edit Journal Entry</h2>
            <p class="muted">Perbarui data jurnal.</p>
        </div>
        <form method="post" action="{{ route('journal-entries.update', $journalEntry) }}">
            @csrf @method('put')
            <div class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2 mb-6">
                <div class="grid gap-1.5"><label>Tanggal</label><input name="entry_date" type="date" value="{{ $journalEntry->entry_date }}" required></div>
                <div class="grid gap-1.5"><label>Reference</label><input name="reference" value="{{ $journalEntry->reference }}"></div>
                <div class="grid gap-1.5 md:col-span-2"><label>Memo</label><input name="memo" value="{{ $journalEntry->memo }}"></div>
            </div>
            <div class="mb-6">
                <h3 class="mb-3">Journal Lines</h3>
                <div id="journal-lines">
                    @foreach($journalEntry->lines as $i => $line)
                        <div class="grid grid-cols-1 gap-3 rounded-2xl border border-slate-200 p-3 mb-3 md:grid-cols-4">
                            <select name="lines[{{ $i }}][chart_account_id]" required><option value="">Pilih akun</option>@foreach($coaOptions as $account)<option value="{{ $account->id }}" @selected($line->chart_account_id===$account->id)>{{ $account->code }} - {{ $account->name }}</option>@endforeach</select>
                            <input name="lines[{{ $i }}][debit]" type="number" min="0" value="{{ $line->debit }}" step="0.01" placeholder="Debit">
                            <input name="lines[{{ $i }}][credit]" type="number" min="0" value="{{ $line->credit }}" step="0.01" placeholder="Credit">
                            <input name="lines[{{ $i }}][description]" value="{{ $line->description }}" placeholder="Deskripsi">
                        </div>
                    @endforeach
                </div>
                <button type="button" class="button ghost text-sm" onclick="addJournalLine()">+ Tambah Line</button>
            </div>
            <div class="mt-4 flex items-center gap-3">
                <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Update Journal
                </button>
                <a class="button ghost" href="{{ route('journal-entries.index') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali
                </a>
            </div>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
let lineIndex = {{ count($journalEntry->lines) }};
function addJournalLine() {
    const html = `<div class="grid grid-cols-1 gap-3 rounded-2xl border border-slate-200 p-3 mb-3 md:grid-cols-4">
        <select name="lines[${lineIndex}][chart_account_id]" required><option value="">Pilih akun</option>@foreach($coaOptions as $account)<option value="{{ $account->id }}">{{ $account->code }} - {{ $account->name }}</option>@endforeach</select>
        <input name="lines[${lineIndex}][debit]" type="number" min="0" value="0" step="0.01" placeholder="Debit">
        <input name="lines[${lineIndex}][credit]" type="number" min="0" value="0" step="0.01" placeholder="Credit">
        <input name="lines[${lineIndex}][description]" placeholder="Deskripsi">
    </div>`;
    document.getElementById('journal-lines').insertAdjacentHTML('beforeend', html);
    lineIndex++;
}
</script>
@endpush
