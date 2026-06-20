@extends('layouts.erp', ['activePage' => 'audit-logs', 'pageTitle' => 'Audit Log'])

@section('content')
<section class="card section wide" id="audit-logs">
    <div class="section-head">
        <h2>Audit Log</h2>
        <span class="badge">{{ $auditLogs->total() }} entri</span>
    </div>
    <form method="get" action="{{ route('audit-logs.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <select name="user_id" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
            <option value="">Semua User</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" @selected(request('user_id')==$user->id)>{{ $user->name }}</option>
            @endforeach
        </select>
        <input type="text" name="action" placeholder="Cari Action..." value="{{ request('action') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 placeholder:text-slate-400 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <div class="flex gap-2 items-end">
            <button class="rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#003d78]">Filter</button>
            <a href="{{ route('audit-logs.index') }}" class="rounded-xl border border-[#d7e3ef] bg-white px-5 py-2.5 text-sm font-bold text-[#0059A7] hover:bg-[#f3f8fc]">Reset</a>
        </div>
    </form>
    <table>
        <thead><tr><th>Waktu</th><th>User</th><th>Action</th><th>Data</th><th>Deskripsi</th></tr></thead>
        <tbody>
            @forelse($auditLogs as $log)
                <tr>
                    <td class="text-xs text-slate-500">{{ $log->created_at->format('d M Y H:i') }}</td>
                    <td class="font-bold">{{ $log->user?->name ?? 'System' }}</td>
                    <td><span class="badge">{{ $log->action }}</span></td>
                    <td class="muted text-xs">{{ class_basename($log->auditable_type) }} #{{ $log->auditable_id }}</td>
                    <td>{{ $log->description }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="py-8 text-center text-slate-500">Belum ada audit log.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($auditLogs->hasPages() || $auditLogs->total() > 0)
        <div class="pager">
            <span>Menampilkan {{ $auditLogs->firstItem() }}-{{ $auditLogs->lastItem() }} dari {{ $auditLogs->total() }}</span>
            {{ $auditLogs->links() }}
        </div>
    @endif
</section>
@endsection
