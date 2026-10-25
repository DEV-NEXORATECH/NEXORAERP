@extends('layouts.erp', ['activePage' => 'audit-logs', 'pageTitle' => 'Audit Log'])

@section('content')
<div class="space-y-4">
    <x-erp.page-header
        title="Riwayat Audit & Aktivitas Sistem"
        subtitle="Log jejak audit keamanan seluruh perubahan data, aksi pengguna, dan histori transaksi."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Administration', 'url' => route('admin.index')], ['label' => 'Audit Logs']]"
    >
        <x-slot:actions>
            <div class="inline-flex items-center gap-1.5 rounded-md border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-xs">
                <span>Total:</span>
                <span class="font-bold text-[#002875]">{{ $auditLogs->total() }} Log Tercatat</span>
            </div>
        </x-slot:actions>
    </x-erp.page-header>

    <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-xs">
        <form method="get" action="{{ route('audit-logs.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <div>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    class="w-full rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
            </div>
            <div>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                    class="w-full rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
            </div>
            <div>
                <select name="user_id" class="w-full rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                    <option value="">Semua User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @selected(request('user_id')==$user->id)>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <input type="text" name="action" placeholder="Cari action..." value="{{ request('action') }}"
                    class="w-full rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 placeholder-slate-400 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-[#001B50] transition-colors">
                    Filter
                </button>
                <a href="{{ route('audit-logs.index') }}" class="inline-flex items-center rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Waktu Kejadian</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Pelaku (User)</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Aksi (Action)</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Entitas Data</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Deskripsi Rincian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($auditLogs as $log)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-4 py-3 text-slate-500 font-mono text-[11px] whitespace-nowrap">
                                {{ $log->created_at->format('d M Y H:i:s') }}
                            </td>
                            <td class="px-4 py-3 text-slate-900 font-semibold">
                                {{ $log->user?->name ?? 'System Automated' }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold border bg-slate-100 text-slate-800 border-slate-200 uppercase">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-600 font-mono text-[11px]">
                                {{ class_basename($log->auditable_type) }} #{{ $log->auditable_id }}
                            </td>
                            <td class="px-4 py-3 text-slate-700">
                                {{ $log->description }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-slate-500">
                                <x-erp.empty-state message="Belum ada catatan aktivitas audit log." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($auditLogs->hasPages() || $auditLogs->total() > 0)
            <div class="px-4 py-3 border-t border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-600">
                <span>Menampilkan {{ $auditLogs->firstItem() }}-{{ $auditLogs->lastItem() }} dari {{ $auditLogs->total() }} entri</span>
                {{ $auditLogs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
