@extends('layouts.erp', ['activePage' => 'chart-account-edit', 'pageTitle' => 'Edit CoA'])

@section('content')
<section class="section">
    <div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
        <div class="mb-8 border-b border-[#d7e3ef] pb-5">
            <h2 class="mb-1">Edit Chart of Account</h2>
            <p class="muted">Perbarui data akun.</p>
        </div>
        <form method="post" action="{{ route('chart-accounts.update', $chartAccount) }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
            @csrf @method('put')
            <div class="grid gap-1.5"><label>Kode</label><input name="code" value="{{ $chartAccount->code }}" required></div>
            <div class="grid gap-1.5"><label>Nama Akun</label><input name="name" value="{{ $chartAccount->name }}" required></div>
            <div class="grid gap-1.5"><label>Tipe</label><select name="type">@foreach(['asset','liability','equity','revenue','expense'] as $type)<option @selected($chartAccount->type===$type)>{{ $type }}</option>@endforeach</select></div>
            <div class="grid gap-1.5"><label>Parent</label><select name="parent_id"><option value="">Root account</option>@foreach($coaOptions as $account)<option value="{{ $account->id }}" @selected($chartAccount->parent_id===$account->id)>{{ $account->code }} - {{ $account->name }}</option>@endforeach</select></div>
            <div class="grid gap-1.5"><label>Status</label><label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" @checked($chartAccount->is_active)> Active</label></div>
            <div class="mt-4 flex items-center gap-3 md:col-span-2">
                <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Update CoA
                </button>
                <a class="button ghost" href="{{ route('chart-accounts.index') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali
                </a>
            </div>
        </form>
    </div>
</section>
@endsection
