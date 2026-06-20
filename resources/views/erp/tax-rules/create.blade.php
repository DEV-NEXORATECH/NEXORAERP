@extends('layouts.erp', ['activePage' => 'tax-rule-create', 'pageTitle' => 'Tambah Tax Rule'])

@section('content')
<section class="section">
    <div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
        <div class="mb-8 border-b border-[#d7e3ef] pb-5">
            <h2 class="mb-1">Tambah Tax Rule</h2>
            <p class="muted">Buat aturan pajak baru.</p>
        </div>
        <form method="post" action="{{ route('tax-rules.store') }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
            @csrf
            <div class="grid gap-1.5"><label>Nama Rule</label><input name="name" placeholder="PPN 11%" required></div>
            <div class="grid gap-1.5"><label>Tax Type</label><select name="tax_type"><option>PPN</option><option>PPh 21</option><option>PPh 23</option><option>PPh 4(2)</option></select></div>
            <div class="grid gap-1.5"><label>Rate %</label><input name="rate" type="number" min="0" step="0.01" required></div>
            <div class="grid gap-1.5"><label>Direction</label><select name="direction"><option>output</option><option>input</option><option>withholding</option></select></div>
            <div class="grid gap-1.5"><label>Status</label><label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" checked> Active</label></div>
            <div class="mt-4 flex items-center gap-3 md:col-span-2">
                <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Simpan
                </button>
                <a class="button ghost" href="{{ route('tax-rules.index') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali
                </a>
            </div>
        </form>
    </div>
</section>
@endsection
