@extends('layouts.erp', ['activePage' => 'bank-reconciliation-items.index', 'pageTitle' => 'Bank Reconciliation'])

@section('content')
<section class="card section wide">
    <div class="section-head">
        <h2>Bank Reconciliation Items</h2>
        @if($can('admin', 'finance'))
        <a class="button ghost" href="{{ route('bank-reconciliation-items.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>
    <table>
        <thead>
            <tr><th>Bank</th><th>Tanggal</th><th>Deskripsi</th><th>Amount</th><th>Tipe</th><th>Reconciled</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($reconciliations as $row)
                <tr>
                    <td class="font-bold">{{ $row->bankAccount?->name ?? '-' }}</td>
                    <td>{{ $row->statement_date }}</td>
                    <td>{{ $row->description ?? $row->reference ?? '-' }}<br><span class="muted">{{ $row->reference ?? '' }}</span></td>
                    <td>{{ $rp($row->amount) }}</td>
                    <td><span class="badge badge-{{ $row->type === 'credit' ? 'active' : 'pending' }}">{{ $row->type }}</span></td>
                    <td><span class="badge badge-{{ $row->reconciled ? 'active' : 'void' }}">{{ $row->reconciled ? 'Yes' : 'No' }}</span></td>
                    <td class="actions">
                        @if($can('admin', 'finance'))<a class="button mini ghost" href="{{ route('bank-reconciliation-items.edit-page', $row) }}">Edit</a>@endif
                        @if($can('admin', 'finance'))<form method="post" action="{{ route('bank-reconciliation-items.destroy', $row) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="py-8 text-center text-slate-500">Belum ada reconciliation item.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($reconciliations->hasPages())
        <div class="pager">
            <div class="text-sm text-slate-500">Menampilkan {{ $reconciliations->firstItem() }}–{{ $reconciliations->lastItem() }} dari {{ $reconciliations->total() }}</div>
            {{ $reconciliations->links() }}
        </div>
    @endif
</section>
@endsection
