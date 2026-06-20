@extends('layouts.erp', ['activePage' => 'procurement-contracts', 'pageTitle' => 'Procurement Contract'])

@section('content')
<section class="card section wide">
    <div class="section-head">
        <h2>Procurement Contract</h2>
        <a class="button ghost" href="{{ route('procurement-contracts.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah Contract
        </a>
    </div>
    <table>
        <thead>
            <tr><th>Contract</th><th>Vendor</th><th>Amount</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($contracts as $row)
                <tr>
                    <td class="font-bold">{{ $row->contract_number }}<br><span class="muted">{{ $row->title }}</span></td>
                    <td>{{ $row->vendor->name ?? '-' }}</td>
                    <td>{{ $rp($row->amount) }}</td>
                    <td><span class="badge badge-{{ $row->status === 'active' ? 'active' : 'pending' }}">{{ $row->status }}</span></td>
                    <td class="actions">
                        <a class="button mini ghost" href="{{ route('procurement-contracts.edit-page', $row) }}">Edit</a>
                        <form method="post" action="{{ route('procurement-contracts.destroy', $row) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="py-8 text-center text-slate-500">Belum ada contract.</td></tr>
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
