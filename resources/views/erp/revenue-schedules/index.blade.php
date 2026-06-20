@extends('layouts.erp', ['activePage' => 'revenue-schedules.index', 'pageTitle' => 'Revenue Schedules'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5" id="revenue-schedules">
    <div class="section-head">
        <h2>Revenue Schedules</h2>
        @if($can('admin', 'finance'))
        <a href="{{ route('revenue-schedules.create-page') }}" class="button ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>

    <form method="get" action="{{ route('revenue-schedules.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        @if(isset($projects))
        <select name="project_id" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
            <option value="">Semua Project</option>
            @foreach($projects as $project)
            <option value="{{ $project->id }}" @selected(request('project_id') == $project->id)>{{ $project->code }} - {{ $project->name }}</option>
            @endforeach
        </select>
        @endif
        <select name="status" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
            <option value="">Semua Status</option>
            <option value="pending" @selected(request('status') == 'pending')>Pending</option>
            <option value="recognized" @selected(request('status') == 'recognized')>Recognized</option>
            <option value="cancelled" @selected(request('status') == 'cancelled')>Cancelled</option>
        </select>
        <div class="flex gap-2 items-end">
            <button class="rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#003d78]">Filter</button>
            <a href="{{ route('revenue-schedules.index') }}" class="rounded-xl border border-[#d7e3ef] bg-white px-5 py-2.5 text-sm font-bold text-[#0059A7] hover:bg-[#f3f8fc]">Reset</a>
        </div>
    </form>

    <table>
        <thead>
            <tr><th>Project</th><th>Tanggal</th><th>Amount</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($schedules as $row)
                <tr>
                    <td class="font-bold">{{ $row->project?->name ?? '-' }}<br><span class="muted">{{ $row->project?->code ?? '' }}</span></td>
                    <td>{{ $row->schedule_date }}</td>
                    <td>{{ $rp($row->amount) }}</td>
                    <td><span class="badge badge-{{ $row->status === 'recognized' ? 'active' : ($row->status === 'cancelled' ? 'void' : 'pending') }}">{{ $row->status }}</span></td>
                    <td class="actions">
                        @if($can('admin', 'finance'))<a class="button mini ghost" href="{{ route('revenue-schedules.edit-page', $row) }}">Edit</a>@endif
                        @if($can('admin', 'finance'))<form method="post" action="{{ route('revenue-schedules.destroy', $row) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="py-8 text-center text-slate-500">Belum ada revenue schedule.</td></tr>
            @endforelse
        </tbody>
    </table>

    @if($schedules->hasPages())
    <div class="pager">
        <span>Menampilkan {{ $schedules->firstItem() }}-{{ $schedules->lastItem() }} dari {{ $schedules->total() }}</span>
        {{ $schedules->links() }}
    </div>
    @endif
</section>
@endsection
