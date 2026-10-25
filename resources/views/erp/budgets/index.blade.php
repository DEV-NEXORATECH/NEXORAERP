@extends('layouts.erp', ['activePage' => 'budgets', 'pageTitle' => 'Budget & Forecast'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#0B2345]/5" id="budgets">
    <div class="section-head">
        <h2>Budget & Forecast</h2>
        @if($can('admin', 'finance'))
        <a class="button ghost" href="{{ route('budgets.create-page') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>

    <form method="get" action="{{ route('budgets.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="field-input">
        @if(isset($projects))
        <select name="project_id" class="field-input">
            <option value="">Semua Project</option>
            @foreach($projects as $project)
            <option value="{{ $project->id }}" @selected(request('project_id') == $project->id)>{{ $project->code }}</option>
            @endforeach
        </select>
        @endif
        <div class="flex gap-2 items-end">
            <button class="filter-btn">Filter</button>
            <a href="{{ route('budgets.index') }}" class="btn-outline">Reset</a>
        </div>
    </form>

    <table>
        <thead><tr><th>Period</th><th>Project</th><th>Account</th><th>Budget</th><th>Forecast</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($budgets as $budget)
                <tr>
                    <td>{{ $budget->period }}</td>
                    <td>{{ $budget->project?->code ?? 'Company' }}</td>
                    <td><span class="muted">{{ $budget->account?->code }} {{ $budget->account?->name ?? '-' }}</span></td>
                    <td>{{ $rp($budget->budget_amount) }}</td>
                    <td>{{ $rp($budget->forecast_amount) }}</td>
                    <td class="actions">
                        @if($can('admin', 'finance'))<a class="button mini ghost" href="{{ route('budgets.edit-page', $budget) }}">Edit</a>@endif
                        @if($can('admin'))<form method="post" action="{{ route('budgets.destroy', $budget) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="py-8 text-center text-slate-500">Belum ada budget.</td></tr>
            @endforelse
        </tbody>
    </table>

    @if($budgets->hasPages())
    <div class="pager">
        <span>Menampilkan {{ $budgets->firstItem() }}-{{ $budgets->lastItem() }} dari {{ $budgets->total() }}</span>
        {{ $budgets->links() }}
    </div>
    @endif
</section>
@endsection
