@extends('layouts.erp', ['activePage' => 'journal-entries', 'pageTitle' => 'Journal Entries'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5" id="journal-entries">
    <div class="section-head">
        <h2>General Ledger & Journaling</h2>
        @if($can('admin', 'finance'))
        <a class="button ghost" href="{{ route('journal-entries.create-page') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>

    <form method="get" action="{{ route('journal-entries.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 placeholder:text-slate-400 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <div class="flex gap-2 items-end">
            <button class="rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#003d78]">Filter</button>
            <a href="{{ route('journal-entries.index') }}" class="rounded-xl border border-[#d7e3ef] bg-white px-5 py-2.5 text-sm font-bold text-[#0059A7] hover:bg-[#f3f8fc]">Reset</a>
        </div>
    </form>

    <table>
        <thead><tr><th>No</th><th>Tanggal</th><th>Reference</th><th>Memo</th><th>Lines</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($journals as $journal)
                <tr>
                    <td class="font-bold">{{ $journal->number }}</td>
                    <td>{{ $journal->entry_date }}</td>
                    <td>{{ $journal->reference ?? '-' }}</td>
                    <td>{{ $journal->memo }}</td>
                    <td>
                        @foreach($journal->lines as $line)
                            <div class="text-xs">{{ $line->account?->code }} {{ $line->account?->name }}: <span class="good">{{ $line->debit > 0 ? 'Debit '.$rp($line->debit) : '' }}</span><span class="bad">{{ $line->credit > 0 ? 'Credit '.$rp($line->credit) : '' }}</span></div>
                        @endforeach
                    </td>
                    <td class="actions">
                        @if($can('admin', 'finance'))<a class="button mini ghost" href="{{ route('journal-entries.edit-page', $journal) }}">Edit</a>@endif
                        @if($can('admin'))<form method="post" action="{{ route('journal-entries.destroy', $journal) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="py-8 text-center text-slate-500">Belum ada journal entry.</td></tr>
            @endforelse
        </tbody>
    </table>

    @if($journals->hasPages())
    <div class="pager">
        <span>Menampilkan {{ $journals->firstItem() }}-{{ $journals->lastItem() }} dari {{ $journals->total() }}</span>
        {{ $journals->links() }}
    </div>
    @endif
</section>
@endsection
