@extends('layouts.erp', ['activePage' => 'budget-edit', 'pageTitle' => 'Edit Budget'])

@section('content')
<section class="section">
    <div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
        <div class="mb-8 border-b border-[#d7e3ef] pb-5">
            <h2 class="mb-1">Edit Budget & Forecast</h2>
            <p class="muted">Perbarui data anggaran.</p>
        </div>
        <form method="post" action="{{ route('budgets.update', $budget) }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
            @csrf @method('put')
            <div class="grid gap-1.5"><label>Period</label><input name="period" value="{{ $budget->period }}" required></div>
            <div class="grid gap-1.5"><label>Project</label><select name="project_id"><option value="">Company</option>@foreach($projects as $project)<option value="{{ $project->id }}" @selected($budget->project_id===$project->id)>{{ $project->code }}</option>@endforeach</select></div>
            <div class="grid gap-1.5 md:col-span-2"><label>CoA</label><select name="chart_account_id"><option value="">No account</option>@foreach($coaOptions as $account)<option value="{{ $account->id }}" @selected($budget->chart_account_id===$account->id)>{{ $account->code }} - {{ $account->name }}</option>@endforeach</select></div>
            <div class="grid gap-1.5"><label>Budget</label><input name="budget_amount" type="number" min="0" value="{{ $budget->budget_amount }}" required></div>
            <div class="grid gap-1.5"><label>Forecast</label><input name="forecast_amount" type="number" min="0" value="{{ $budget->forecast_amount }}" required></div>
            <div class="grid gap-1.5 md:col-span-2"><label>Notes</label><input name="notes" value="{{ $budget->notes }}"></div>
            <div class="mt-4 flex items-center gap-3 md:col-span-2">
                <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Update
                </button>
                <a class="button ghost" href="{{ route('budgets.index') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali
                </a>
            </div>
        </form>
    </div>
</section>
@endsection
