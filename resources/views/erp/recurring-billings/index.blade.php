@extends('layouts.erp', ['activePage' => 'recurring-billings', 'pageTitle' => 'Recurring Billings'])

@section('content')
<section class="card section wide">
    <div class="section-head">
        <h2>Recurring & Subscription Billing</h2>
        @if($can('admin', 'finance'))
        <a class="button ghost" href="{{ route('recurring-billings.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>
    <table>
        <thead><tr><th>Name</th><th>Client</th><th>Frequency</th><th>Amount</th><th>Next Invoice</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($recurrings as $row)
                <tr>
                    <td class="font-bold">{{ $row->name }}</td>
                    <td>{{ $row->client?->name ?? '-' }}</td>
                    <td>{{ $row->frequency }}</td>
                    <td>{{ $rp($row->amount) }}</td>
                    <td>{{ $row->next_invoice_date }}</td>
                    <td><span class="badge badge-{{ $row->status === 'active' ? 'active' : ($row->status === 'paused' ? 'pending' : 'void') }}">{{ $row->status }}</span></td>
                    <td class="actions">
                        @if($can('admin', 'finance'))<a class="button mini ghost" href="{{ route('recurring-billings.edit-page', $row) }}">Edit</a>@endif
                        @if($can('admin'))<form method="post" action="{{ route('recurring-billings.destroy', $row) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="py-8 text-center text-slate-500">Belum ada recurring billing.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($recurrings->hasPages())
        <div class="pager">
            <div class="text-sm text-slate-500">{{ $recurrings->firstItem() }}–{{ $recurrings->lastItem() }} dari {{ $recurrings->total() }}</div>
            {{ $recurrings->links() }}
        </div>
    @endif
</section>
@endsection
