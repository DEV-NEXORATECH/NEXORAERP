@extends('layouts.erp', ['activePage' => 'sales-inquiries.create-page', 'pageTitle' => 'Tambah Inquiry'])

@section('content')
<section class="section">
    <div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
        <div class="mb-8 border-b border-[#d7e3ef] pb-5">
            <h2 class="mb-1">Tambah Inquiry</h2>
            <p class="muted">Catat inquiry sales baru.</p>
        </div>
        <form method="post" action="{{ route('sales-inquiries.store') }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
            @csrf
            <div class="grid gap-1.5"><label>Perusahaan</label><input name="company_name" required></div>
            <div class="grid gap-1.5"><label>Kontak</label><input name="contact_name"></div>
            <div class="grid gap-1.5"><label>Email</label><input name="email" type="email"></div>
            <div class="grid gap-1.5"><label>Telepon</label><input name="phone"></div>
            <div class="grid gap-1.5"><label>Source</label><input name="source" placeholder="Website / referral / event"></div>
            <div class="grid gap-1.5"><label>Kebutuhan</label><input name="need"></div>
            <div class="grid gap-1.5"><label>Status</label>
                <select name="status">
                    <option value="new">New</option>
                    <option value="contacted">Contacted</option>
                    <option value="qualified">Qualified</option>
                    <option value="lost">Lost</option>
                </select>
            </div>
            <div class="grid gap-1.5"><label>Owner</label>
                <select name="owner_id">
                    <option value="">—</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid gap-1.5 md:col-span-2"><label>Notes</label><textarea name="notes" rows="3"></textarea></div>
            <div class="mt-4 flex items-center gap-3 md:col-span-2">
                <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Simpan Inquiry
                </button>
                <a class="button ghost" href="{{ route('sales-inquiries.index') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali
                </a>
            </div>
        </form>
    </div>
</section>
@endsection
