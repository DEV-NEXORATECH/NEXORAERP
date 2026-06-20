@extends('layouts.erp', ['activePage' => 'client-contracts.index', 'pageTitle' => 'Client Contracts'])

@section('content')
<section class="card section wide">
    <div class="section-head">
        <h2>Client Contracts</h2>
        @if($can('admin', 'sales'))
        <a class="button ghost" href="{{ route('client-contracts.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>
    <table>
        <thead>
            <tr><th>Title</th><th>Contract #</th><th>Client</th><th>Period</th><th>Amount</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($contracts as $row)
                <tr>
                    <td class="font-bold">{{ $row->title }}</td>
                    <td><span class="muted">{{ $row->contract_number }}</span></td>
                    <td>{{ $row->client->name ?? '-' }}</td>
                    <td>{{ $row->start_date ? \Carbon\Carbon::parse($row->start_date)->format('d M Y') : '-' }} – {{ $row->end_date ? \Carbon\Carbon::parse($row->end_date)->format('d M Y') : '-' }}</td>
                    <td class="font-bold">{{ $rp($row->amount) }}</td>
                    <td><span class="badge badge-{{ $row->status === 'active' ? 'active' : ($row->status === 'expired' ? 'void' : 'pending') }}">{{ $row->status }}</span></td>
                    <td class="actions">
                        @if($can('admin', 'sales'))<a class="button mini ghost" href="{{ route('client-contracts.edit-page', $row) }}">Edit</a>@endif
                        @if($can('admin', 'sales'))<form method="post" action="{{ route('client-contracts.destroy', $row) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="py-8 text-center text-slate-500">Belum ada contract.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($contracts->hasPages())
        <div class="pager">
            <div class="text-sm text-slate-500">Menampilkan {{ $contracts->firstItem() }}–{{ $contracts->lastItem() }} dari {{ $contracts->total() }}</div>
            {{ $contracts->links() }}
        </div>
    @endif
</section>
@endsection
