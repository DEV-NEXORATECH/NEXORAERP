@extends('layouts.erp', ['activePage' => 'sales-leads.edit-page', 'pageTitle' => 'Edit Lead'])

@section('content')
<div class="space-y-5 max-w-4xl">
    <x-erp.page-header
        title="Edit Sales Lead / Opportunity"
        subtitle="Perbarui nilai kontrak, progres stage, dan probabilitas closing untuk: {{ $lead->title }}"
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Sales & CRM', 'url' => route('sales-crm.index')], ['label' => 'Sales Leads', 'url' => route('sales-leads.index')], ['label' => 'Edit']]"
    />

    <div class="rounded-lg border border-[#D1D5DB] bg-white p-6 shadow-xs">
        <form method="post" action="{{ route('sales-leads.update', $lead) }}" class="space-y-5">
            @csrf
            @method('put')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Judul Peluang / Opportunity Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title', $lead->title) }}" required autofocus
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                    @error('title')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Tahapan Pipeline (Stage) <span class="text-red-500">*</span>
                    </label>
                    <select name="stage" required
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                        <option value="qualified" @selected($lead->stage === 'qualified')>Qualified (Kualifikasi Terpenuhi)</option>
                        <option value="proposal" @selected($lead->stage === 'proposal')>Proposal (Penyusunan Penawaran)</option>
                        <option value="negotiation" @selected($lead->stage === 'negotiation')>Negotiation (Negosiasi Kontrak)</option>
                        <option value="won" @selected($lead->stage === 'won')>Won (Berhasil Ditutup)</option>
                        <option value="lost" @selected($lead->stage === 'lost')>Lost (Gagal / Dibatalkan)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Estimasi Nilai Kontrak (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="value" value="{{ old('value', $lead->value) }}" min="0" step="1" required
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Probabilitas Keberhasilan (%) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="probability" value="{{ old('probability', $lead->probability) }}" min="0" max="100" required
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Target Tanggal Closing (Expected Close)
                    </label>
                    <input type="date" name="expected_close_date" value="{{ old('expected_close_date', $lead->expected_close_date ? \Carbon\Carbon::parse($lead->expected_close_date)->format('Y-m-d') : '') }}"
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Sumber Sales Inquiry Terkait
                    </label>
                    <select name="sales_inquiry_id"
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                        <option value="">-- Tanpa Inquiry Sebelumnya --</option>
                        @foreach($salesInquiries as $inquiry)
                            <option value="{{ $inquiry->id }}" @selected($lead->sales_inquiry_id === $inquiry->id)>
                                {{ $inquiry->company_name }} ({{ $inquiry->need ?: 'Inquiry' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Klien Terdaftar
                    </label>
                    <select name="client_id"
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                        <option value="">-- Pilih Klien Terdaftar (Opsional) --</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" @selected($lead->client_id === $client->id)>
                                {{ $client->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        PIC Sales Penanggung Jawab
                    </label>
                    <select name="owner_id"
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                        <option value="">-- Pilih Sales Owner --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected($lead->owner_id === $user->id)>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Catatan Kualifikasi & Strategi Penawaran
                    </label>
                    <textarea name="notes" rows="3"
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">{{ old('notes', $lead->notes) }}</textarea>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-4 py-2 text-xs font-semibold text-white shadow-xs hover:bg-[#001B50] transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                    Perbarui Lead
                </button>
                <a href="{{ route('sales-leads.index') }}" class="inline-flex items-center gap-1.5 rounded-md border border-[#D1D5DB] bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-[#F3F4F6] transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
