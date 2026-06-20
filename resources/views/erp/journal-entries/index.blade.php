@extends('layouts.erp', ['activePage' => 'journal-entries', 'pageTitle' => 'Journal Entries'])

@section('content')
<section class="card section wide">
    <div class="section-head">
        <h2>General Ledger & Journaling</h2>
        @if($can('admin', 'finance'))
        <a class="button ghost" href="{{ route('journal-entries.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>
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
            <div class="text-sm text-slate-500">{{ $journals->firstItem() }}–{{ $journals->lastItem() }} dari {{ $journals->total() }}</div>
            {{ $journals->links() }}
        </div>
    @endif
</section>
@endsection
