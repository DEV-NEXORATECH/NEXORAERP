@extends('layouts.erp', ['activePage' => 'payment-reminders', 'pageTitle' => 'Payment Reminders'])

@section('content')
<section class="card section wide">
    <div class="section-head">
        <h2>Automated Payment Reminder</h2>
        @if($can('admin', 'finance'))
        <a class="button ghost" href="{{ route('payment-reminders.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>
    <table>
        <thead><tr><th>Invoice</th><th>Reminder Date</th><th>Channel</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($reminders as $row)
                <tr>
                    <td class="font-bold">{{ $row->invoice?->number ?? '-' }}</td>
                    <td>{{ $row->reminder_date }}</td>
                    <td>{{ $row->channel }}</td>
                    <td><span class="badge badge-{{ $row->status === 'scheduled' ? 'pending' : ($row->status === 'sent' ? 'active' : 'void') }}">{{ $row->status }}</span></td>
                    <td class="actions">
                        @if($can('admin', 'finance'))<a class="button mini ghost" href="{{ route('payment-reminders.edit-page', $row) }}">Edit</a>@endif
                        @if($can('admin'))<form method="post" action="{{ route('payment-reminders.destroy', $row) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="py-8 text-center text-slate-500">Belum ada payment reminder.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($reminders->hasPages())
        <div class="pager">
            <div class="text-sm text-slate-500">{{ $reminders->firstItem() }}–{{ $reminders->lastItem() }} dari {{ $reminders->total() }}</div>
            {{ $reminders->links() }}
        </div>
    @endif
</section>
@endsection
