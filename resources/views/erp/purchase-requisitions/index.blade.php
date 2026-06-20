@extends('layouts.erp', ['activePage' => 'purchase-requisitions', 'pageTitle' => 'Purchase Requisition'])

@section('content')
<section class="card section wide">
    <div class="section-head">
        <h2>Purchase Requisition</h2>
        <a class="button ghost" href="{{ route('purchase-requisitions.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah PR
        </a>
    </div>
    <table>
        <thead>
            <tr><th>PR</th><th>Pemohon</th><th>Amount</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($requisitions as $row)
                <tr>
                    <td class="font-bold">{{ $row->number }}<br><span class="muted">{{ $row->title }}</span></td>
                    <td>{{ $row->requester->name ?? '-' }}<br><span class="muted">{{ $row->department->name ?? '-' }}</span></td>
                    <td>{{ $rp($row->amount) }}</td>
                    <td><span class="badge badge-{{ $row->status === 'approved' ? 'active' : ($row->status === 'rejected' ? 'void' : 'pending') }}">{{ $row->status }}</span></td>
                    <td class="actions">
                        <a class="button mini ghost" href="{{ route('purchase-requisitions.edit-page', $row) }}">Edit</a>
                        <form method="post" action="{{ route('purchase-requisitions.destroy', $row) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="py-8 text-center text-slate-500">Belum ada purchase requisition.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($requisitions->hasPages())
        <div class="pager">
            <div class="text-sm text-slate-500">Menampilkan {{ $requisitions->firstItem() }}–{{ $requisitions->lastItem() }} dari {{ $requisitions->total() }}</div>
            {{ $requisitions->links() }}
        </div>
    @endif
</section>
@endsection
