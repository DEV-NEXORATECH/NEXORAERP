@extends('layouts.erp', ['activePage' => 'recurring-billings', 'pageTitle' => 'Recurring Billings'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#0B2345]/5" id="recurring-billings">
    <div class="section-head">
        <h2>Recurring & Subscription Billing</h2>
        @if($can('admin', 'finance'))
        <a class="button ghost" href="{{ route('recurring-billings.create-page') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>

    <form method="get" action="{{ route('recurring-billings.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="field-input">
        <select name="status" class="field-input">
            <option value="">Semua Status</option>
            <option value="active" @selected(request('status') == 'active')>Active</option>
            <option value="paused" @selected(request('status') == 'paused')>Paused</option>
            <option value="ended" @selected(request('status') == 'ended')>Ended</option>
        </select>
        <div class="flex gap-2 items-end">
            <button class="filter-btn">Filter</button>
            <a href="{{ route('recurring-billings.index') }}" class="btn-outline">Reset</a>
        </div>
    </form>

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
        <span>Menampilkan {{ $recurrings->firstItem() }}-{{ $recurrings->lastItem() }} dari {{ $recurrings->total() }}</span>
        {{ $recurrings->links() }}
    </div>
    @endif
</section>
@endsection
