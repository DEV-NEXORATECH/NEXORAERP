@extends('layouts.erp', ['activePage' => 'budgets', 'pageTitle' => 'Budget & Forecast'])

@section('content')
<section class="card section wide">
    <div class="section-head">
        <h2>Budget & Forecast</h2>
        @if($can('admin', 'finance'))
        <a class="button ghost" href="{{ route('budgets.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>
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
            <div class="text-sm text-slate-500">{{ $budgets->firstItem() }}–{{ $budgets->lastItem() }} dari {{ $budgets->total() }}</div>
            {{ $budgets->links() }}
        </div>
    @endif
</section>
@endsection
