@extends('layouts.erp', ['activePage' => 'projects', 'pageTitle' => 'Projects'])

@section('content')

{{-- Project Finance Cards --}}
<section class="space-y-4 mb-6>
    <div class="section-head">
        <h2>Project Finance Detail</h2>
        <span class="badge">{{ $projectReports->count() }} project</span>
    </div>
    <div class="grid three">
        @forelse ($projectReports as $report)
            <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm overflow-x-auto">
                <div class="mb-4 flex items-start justify-between gap-3">
                    <div>
                        <h3 class="mb-0.5">
                            <a href="{{ route('projects.show', $report['project']) }}" class="hover:text-[#002875]">
                                {{ $report['project']->code }} — {{ $report['project']->name }}
                            </a>
                        </h3>
                        <div class="muted">{{ $report['project']->client }}</div>
                    </div>
                    <span class="badge badge-{{ $report['project']->status }}">{{ $report['project']->status }}</span>
                </div>
                <table class="table-folk w-full">
                    <tr><td class="text-slate-500">Kontrak</td><td class="text-right font-bold">{{ $rp($report['project']->contract_value) }}</td></tr>
                    <tr><td class="text-slate-500">Income</td><td class="good text-right font-bold">{{ $rp($report['summary']['income']) }}</td></tr>
                    <tr><td class="text-slate-500">Expense</td><td class="bad text-right font-bold">{{ $rp($report['summary']['expense']) }}</td></tr>
                    <tr><td class="text-slate-500">Profit/Loss</td><td class="{{ $report['summary']['balance'] >= 0 ? 'good' : 'bad' }} text-right font-black">{{ $rp($report['summary']['balance']) }}</td></tr>
                    <tr><td class="text-slate-500">Margin</td><td class="text-right font-bold">{{ number_format($report['profit_margin'], 2) }}%</td></tr>
                    <tr><td class="text-slate-500">Salary</td><td class="text-right">{{ $rp($report['salary_total']) }}</td></tr>
                    <tr><td class="text-slate-500">Reimburse</td><td class="text-right">{{ $rp($report['reimbursement_total']) }}</td></tr>
                    <tr><td class="text-slate-500">Invoice</td><td class="text-right">{{ $rp($report['invoice_total']) }}</td></tr>
                </table>
            </div>
        @empty
            <div class="col-span-full rounded-lg bg-[#F4F7FB] p-8 text-center text-sm font-bold text-slate-500">Belum ada data project.</div>
        @endforelse
    </div>
</section>

{{-- Projects Table --}}
<section class="bg-white rounded-lg border border-gray-200 p-5 shadow-sm mt-4">
    <div class="section-head">
        <h2>Daftar Projects</h2>
        @if($can('admin', 'sales'))
        <x-erp.toolbar-actions module="projects" :create-route="route('projects.create-page')" create-label="+ Tambah" />
        @endif
    </div>
    
    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-card">
        <form method="get" action="{{ route('projects.index') }}" class="filter-toolbar">
            
        <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="filter-search pl-8 pr-3 text-xs">
        <select name="status" class="field-input">
            <option value="">Semua Status</option>
            <option value="planning" @selected(request('status')==='planning')>Planning</option>
            <option value="active" @selected(request('status')==='active')>Active</option>
            <option value="done" @selected(request('status')==='done')>Done</option>
            <option value="hold" @selected(request('status')==='hold')>Hold</option>
        </select>
        <div class="flex gap-2 items-end">
            <button class="filter-btn">Filter</button>
            <a href="{{ route('projects.index') }}" class="btn-outline">Reset</a>
        </div>
    
        </form>
    </div>

    <table class="table-folk w-full">
        <thead><tr><th>Project</th><th>Status</th><th>Kontrak / Budget</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($projectsPage as $project)
                <tr>
                    <td>
                        <div class="font-black">{{ $project->code }}</div>
                        <div class="muted">{{ $project->name }} · {{ $project->client }}</div>
                    </td>
                    <td><span class="badge badge-{{ $project->status }}">{{ $project->status }}</span></td>
                    <td>
                        <div class="font-bold">{{ $rp($project->contract_value) }}</div>
                        <div class="muted">Budget {{ $rp($project->budget) }}</div>
                    </td>
                    <td class="actions">
                        <div class="inline-flex items-center gap-1.5">
                            <a href="{{ route('projects.show', $project) }}" class="inline-flex h-7 w-7 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE]" title="Lihat Detail">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        </a>
                            @if($can('admin', 'sales'))
                                <a href="{{ route('projects.edit-page', $project) }}" class="inline-flex h-7 w-7 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE]" title="Edit">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                        </a>
                            @endif
                            @if($can('admin'))
                                <form method="post" action="{{ route('projects.destroy', $project) }}" class="inline" onsubmit="return confirm('Hapus project ini?');">
                            @csrf
                            @method('delete')
                            <button type="submit" class="inline-flex h-7 w-7 items-center justify-center rounded border border-[#FECACA] bg-[#FEF2F2] text-[#DC2626] hover:bg-[#FEE2E2]" title="Hapus">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                            </button>
                        </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="py-8 text-center text-slate-500">Belum ada project.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($projectsPage->hasPages() || $projectsPage->total() > 0)
        <div class="pagination-card flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-lg border border-[#E5E7EB] bg-white px-4 py-3 text-xs text-[#475569] shadow-xs gap-3"><span>Menampilkan {{ $projectsPage->firstItem() }}-{{ $projectsPage->lastItem() }} dari {{ $projectsPage->total() }}</span>
            {{ $projectsPage->links() }}</div>
    @endif
</section>


<x-erp.import-modal module="projects" title="Impor Data Projects" />
@endsection