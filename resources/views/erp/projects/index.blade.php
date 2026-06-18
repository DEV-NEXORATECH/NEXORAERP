@extends('layouts.erp', ['activePage' => 'projects', 'pageTitle' => 'Projects'])

@section('content')

{{-- Project Finance Cards --}}
<section class="section" id="project-finance">
    <div class="section-head">
        <h2>Project Finance Detail</h2>
        <span class="badge">{{ $projectReports->count() }} project</span>
    </div>
    <div class="grid three">
        @forelse ($projectReports as $report)
            <div class="card overflow-x-auto">
                <div class="mb-4 flex items-start justify-between gap-3">
                    <div>
                        <h3 class="mb-0.5">
                            <a href="{{ route('projects.show', $report['project']) }}" class="hover:text-[#0059A7]">
                                {{ $report['project']->code }} — {{ $report['project']->name }}
                            </a>
                        </h3>
                        <div class="muted">{{ $report['project']->client }}</div>
                    </div>
                    <span class="badge badge-{{ $report['project']->status }}">{{ $report['project']->status }}</span>
                </div>
                <table>
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
            <div class="col-span-full rounded-2xl bg-[#f3f8fc] p-8 text-center text-sm font-bold text-slate-500">Belum ada data project.</div>
        @endforelse
    </div>
</section>

{{-- Projects Table --}}
<section class="card section wide">
    <div class="section-head">
        <h2>Daftar Projects</h2>
        @if($can('admin', 'sales'))
        <a class="button ghost" href="{{ route('projects.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>
    <table>
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
                        <a class="button mini ghost" href="{{ route('projects.show', $project) }}">Lihat</a>
                        @if($can('admin', 'sales'))<a class="button mini ghost" href="{{ route('projects.edit-page', $project) }}">Edit</a>@endif
                        @if($can('admin'))<form method="post" action="{{ route('projects.destroy', $project) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="py-8 text-center text-slate-500">Belum ada project.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($projectsPage->hasPages())
        <div class="pager">
            <div class="text-sm text-slate-500">{{ $projectsPage->firstItem() }}–{{ $projectsPage->lastItem() }} dari {{ $projectsPage->total() }}</div>
            {{ $projectsPage->links() }}
        </div>
    @endif
</section>

@endsection
