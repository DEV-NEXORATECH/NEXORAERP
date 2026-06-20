@extends('layouts.erp', ['activePage' => 'tax-rule-edit', 'pageTitle' => 'Edit Tax Rule'])

@section('content')
<section class="section">
    <div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
        <div class="mb-8 border-b border-[#d7e3ef] pb-5">
            <h2 class="mb-1">Edit Tax Rule</h2>
            <p class="muted">Perbarui aturan pajak.</p>
        </div>
        <form method="post" action="{{ route('tax-rules.update', $taxRule) }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
            @csrf @method('put')
            <div class="grid gap-1.5"><label>Nama Rule</label><input name="name" value="{{ $taxRule->name }}" required></div>
            <div class="grid gap-1.5"><label>Tax Type</label><select name="tax_type">@foreach(['PPN','PPh 21','PPh 23','PPh 4(2)'] as $type)<option @selected($taxRule->tax_type===$type)>{{ $type }}</option>@endforeach</select></div>
            <div class="grid gap-1.5"><label>Rate %</label><input name="rate" type="number" min="0" step="0.01" value="{{ $taxRule->rate }}" required></div>
            <div class="grid gap-1.5"><label>Direction</label><select name="direction">@foreach(['output','input','withholding'] as $dir)<option @selected($taxRule->direction===$dir)>{{ $dir }}</option>@endforeach</select></div>
            <div class="grid gap-1.5"><label>Status</label><label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" @checked($taxRule->is_active)> Active</label></div>
            <div class="mt-4 flex items-center gap-3 md:col-span-2">
                <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Update
                </button>
                <a class="button ghost" href="{{ route('tax-rules.index') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali
                </a>
            </div>
        </form>
    </div>
</section>
@endsection
