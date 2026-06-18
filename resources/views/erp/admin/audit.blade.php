@extends('layouts.erp', ['activePage' => 'audit', 'pageTitle' => 'Audit Log'])

@section('content')
<section class="card section wide" id="audit">
    <div class="section-head">
        <h2>Audit Log</h2>
        <span class="badge">{{ $auditLogs->total() }} entri</span>
    </div>
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
    @if($auditLogs->hasPages())
        <div class="pager">
            <div class="text-sm text-slate-500">{{ $auditLogs->firstItem() }}–{{ $auditLogs->lastItem() }} dari {{ $auditLogs->total() }}</div>
            {{ $auditLogs->links() }}
        </div>
    @endif
</section>
@endsection
