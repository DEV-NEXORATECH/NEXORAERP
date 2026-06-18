@extends('layouts.erp', ['activePage' => 'sales', 'pageTitle' => 'Sales & Proposals'])

@section('content')
<section class="card section wide">
    <div class="section-head">
        <h2>Proposal Workflow</h2>
        <a class="button ghost" href="{{ route('proposals.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
    </div>
    <table>
        <thead><tr><th>Proposal</th><th>Status</th><th>Amount</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($proposalsPage as $proposal)
                <tr>
                    <td>
                        <div class="font-bold">{{ $proposal->title }}</div>
                        <div class="muted">{{ $proposal->project->code }}</div>
                    </td>
                    <td>
                        <form method="post" action="{{ route('proposals.status', $proposal) }}" class="toolbar">
                            @csrf @method('patch')
                            <select name="status" class="w-auto min-h-9 py-1.5 text-xs">
                                @foreach($statusOptions['proposal'] as $status)<option @selected($proposal->status===$status)>{{ $status }}</option>@endforeach
                            </select>
                            <button class="mini">Simpan</button>
                        </form>
                    </td>
                    <td class="font-bold">{{ $rp($proposal->amount) }}</td>
                    <td class="actions">
                        <a class="button mini ghost" href="{{ route('proposals.pdf', $proposal) }}" target="_blank">Lihat</a>
                        <a class="button mini ghost" href="{{ route('proposals.edit-page', $proposal) }}">Edit</a>
                        @if($can('admin'))<form method="post" action="{{ route('proposals.destroy', $proposal) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="py-8 text-center text-slate-500">Belum ada proposal.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($proposalsPage->hasPages())
        <div class="pager">
            <div class="text-sm text-slate-500">{{ $proposalsPage->firstItem() }}–{{ $proposalsPage->lastItem() }} dari {{ $proposalsPage->total() }}</div>
            {{ $proposalsPage->links() }}
        </div>
    @endif
</section>
@endsection
