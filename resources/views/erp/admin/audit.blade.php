@extends('layouts.erp', ['activePage' => 'audit', 'pageTitle' => 'Audit Log'])

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3.5">
            <a href="{{ route('admin.index') }}" class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-md border border-[#E5E7EB] bg-white text-[#475569] transition hover:border-[#002875] hover:text-[#002875]" title="Kembali ke Admin">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-[#002875]">Security & Compliance</span>
                    <span class="text-[#475569]">·</span>
                    <span class="text-[11px] font-medium text-[#475569]">System Activity</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#0F172A]">Audit Log Trail</h1>
                <p class="mt-0.5 text-xs text-[#475569]">Catatan jejak aktivitas pengguna sistem, pembuatan, pengubahan, dan penghapusan data secara kronologis.</p>
            </div>
        </div>

        <x-erp.toolbar-actions module="audit_logs" :can-create="false" />
    </div>

    {{-- Audit Log Table --}}
    <div class="rounded-lg border border-[#E5E7EB] bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead class="bg-[#F8FAFC] border-b border-[#E5E7EB] text-[#475569] font-semibold">
                    <tr>
                        <th class="px-5 py-3.5">Waktu</th>
                        <th class="px-4 py-3.5">Pengguna</th>
                        <th class="px-4 py-3.5">Aksi</th>
                        <th class="px-4 py-3.5">Entitas / Objek</th>
                        <th class="px-5 py-3.5">Deskripsi Perubahan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @forelse($auditLogs as $log)
                    <tr class="hover:bg-[#F8FAFC]/60 transition">
                        <td class="px-5 py-3.5 whitespace-nowrap text-[#475569]">
                            {{ $log->created_at->format('d M Y H:i:s') }}
                        </td>
                        <td class="px-4 py-3.5 font-bold text-[#0F172A]">
                            {{ $log->user?->name ?? 'System Automated' }}
                        </td>
                        <td class="px-4 py-3.5">
                            @php
                                $actionBadge = match(strtolower($log->action)) {
                                    'create', 'store', 'tambah' => 'bg-[#F0FDF4] text-[#16A34A] border border-[#86EFAC]',
                                    'delete', 'destroy', 'hapus' => 'bg-[#FEF2F2] text-[#DC2626] border border-[#FCA5A5]',
                                    'update', 'edit', 'ubah' => 'bg-[#EFF6FF] text-[#0284C7] border border-[#BAE6FD]',
                                    default => 'bg-[#F1F5F9] text-[#475569] border border-[#CBD5E1]'
                                };
                            @endphp
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $actionBadge }}">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5 font-mono text-[11px] text-[#475569]">
                            {{ class_basename($log->auditable_type) }} #{{ $log->auditable_id }}
                        </td>
                        <td class="px-5 py-3.5 text-[#0F172A]">
                            {{ $log->description ?: '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-xs text-[#94A3B8]">
                            Belum ada catatan aktivitas di audit log.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <x-erp.pagination :paginator="$auditLogs" />
</div>
@endsection
