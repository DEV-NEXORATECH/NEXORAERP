@extends('layouts.erp', ['activePage' => 'client-contracts.edit-page', 'pageTitle' => 'Edit Kontrak Klien'])

@section('content')
<div class="space-y-5 max-w-4xl">
    <x-erp.page-header
        title="Edit Kontrak Klien"
        subtitle="Perbarui masa berlaku, status operasional, dan nilai kontrak: {{ $contract->contract_number }} - {{ $contract->title }}"
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Sales & CRM', 'url' => route('sales-crm.index')], ['label' => 'Client Contracts', 'url' => route('client-contracts.index')], ['label' => 'Edit']]"
    />

    <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-xs">
        <form method="post" action="{{ route('client-contracts.update', $contract) }}" class="space-y-5">
            @csrf
            @method('put')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Judul Kontrak Kerja Sama <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title', $contract->title) }}" required autofocus
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                    @error('title')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Klien / Mitra <span class="text-red-500">*</span>
                    </label>
                    <select name="client_id" required
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" @selected($contract->client_id === $client->id)>{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Total Nilai Kontrak (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="amount" value="{{ old('amount', $contract->amount) }}" min="0" step="1" required
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Tanggal Mulai Berlaku (Start Date)
                    </label>
                    <input type="date" name="start_date" value="{{ old('start_date', $contract->start_date ? \Carbon\Carbon::parse($contract->start_date)->format('Y-m-d') : '') }}"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Tanggal Berakhir (End Date)
                    </label>
                    <input type="date" name="end_date" value="{{ old('end_date', $contract->end_date ? \Carbon\Carbon::parse($contract->end_date)->format('Y-m-d') : '') }}"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Tanggal Pengingat Renewal (Reminder)
                    </label>
                    <input type="date" name="reminder_date" value="{{ old('reminder_date', $contract->reminder_date ? \Carbon\Carbon::parse($contract->reminder_date)->format('Y-m-d') : '') }}"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Status Kontrak <span class="text-red-500">*</span>
                    </label>
                    <select name="status" required
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                        <option value="draft" @selected($contract->status === 'draft')>Draft (Penyusunan)</option>
                        <option value="active" @selected($contract->status === 'active')>Active (Berlaku)</option>
                        <option value="expired" @selected($contract->status === 'expired')>Expired (Selesai/Kedaluwarsa)</option>
                        <option value="terminated" @selected($contract->status === 'terminated')>Terminated (Dihentikan)</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Ruang Lingkup & Ketentuan Tambahan
                    </label>
                    <textarea name="notes" rows="3"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">{{ old('notes', $contract->notes) }}</textarea>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-4 py-2 text-xs font-semibold text-white shadow-xs hover:bg-[#001B50] transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                    Perbarui Kontrak
                </button>
                <a href="{{ route('client-contracts.index') }}" class="inline-flex items-center gap-1.5 rounded-md border border-slate-300 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
