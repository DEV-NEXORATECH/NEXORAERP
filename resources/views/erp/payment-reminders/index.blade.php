@extends('layouts.erp', ['activePage' => 'payment-reminders', 'pageTitle' => 'Payment Reminders'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5" id="payment-reminders">
    <div class="section-head">
        <h2>Automated Payment Reminder</h2>
        @if($can('admin', 'finance'))
        <a class="button ghost" href="{{ route('payment-reminders.create-page') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>

    <form method="get" action="{{ route('payment-reminders.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <select name="status" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
            <option value="">Semua Status</option>
            <option value="scheduled" @selected(request('status') == 'scheduled')>Scheduled</option>
            <option value="sent" @selected(request('status') == 'sent')>Sent</option>
            <option value="cancelled" @selected(request('status') == 'cancelled')>Cancelled</option>
        </select>
        <select name="channel" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
            <option value="">Semua Channel</option>
            <option value="email" @selected(request('channel') == 'email')>Email</option>
            <option value="whatsapp" @selected(request('channel') == 'whatsapp')>WhatsApp</option>
            <option value="phone" @selected(request('channel') == 'phone')>Phone</option>
        </select>
        <div class="flex gap-2 items-end">
            <button class="rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#003d78]">Filter</button>
            <a href="{{ route('payment-reminders.index') }}" class="rounded-xl border border-[#d7e3ef] bg-white px-5 py-2.5 text-sm font-bold text-[#0059A7] hover:bg-[#f3f8fc]">Reset</a>
        </div>
    </form>

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
        <span>Menampilkan {{ $reminders->firstItem() }}-{{ $reminders->lastItem() }} dari {{ $reminders->total() }}</span>
        {{ $reminders->links() }}
    </div>
    @endif
</section>
@endsection
