@extends('layouts.erp', ['activePage' => 'journal-entry-edit', 'pageTitle' => 'Edit Journal Entry'])

@section('content')
<div class="max-w-4xl mx-auto space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-[#0F172A] tracking-tight">Edit Jurnal Transaksi (Journal Entries)</h1>
            <p class="text-xs text-[#475569]">Lengkapi formulir di bawah ini dengan data yang valid</p>
        </div>
        <a href="{{ route('journal-entries.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-[#475569] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F8FAFC] transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg border border-[#D1D5DB] p-6 shadow-sm">
        <form method="post" action="{{ route('journal-entries.update', $journalEntry) }}" class="space-y-4">
            
            @csrf @method('put')
            <div class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2 mb-6">
                <div class="grid gap-1.5"><label>Tanggal</label><input name="entry_date" type="date" value="{{ $journalEntry->entry_date }}" required class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]"></div>
                <div class="grid gap-1.5"><label>Reference</label><input name="reference" value="{{ $journalEntry->reference }}" class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]"></div>
                <div class="grid gap-1.5 md:col-span-2"><label>Memo</label><input name="memo" value="{{ $journalEntry->memo }}" class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]"></div>
            </div>
            <div class="mb-6">
                <h3 class="mb-3">Journal Lines</h3>
                <div id="journal-lines">
                    @foreach($journalEntry->lines as $i => $line)
                        <div class="grid grid-cols-1 gap-3 rounded-2xl border border-[#D1D5DB] p-3 mb-3 md:grid-cols-4">
                            <select name="lines[{{ $i }}][chart_account_id]" required class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]"><option value="">Pilih akun</option>@foreach($coaOptions as $account)<option value="{{ $account->id }}" @selected($line->chart_account_id===$account->id)>{{ $account->code }} - {{ $account->name }}</option>@endforeach</select>
                            <input name="lines[{{ $i }}][debit]" type="number" min="0" value="{{ $line->debit }}" step="0.01" placeholder="Debit" class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]">
                            <input name="lines[{{ $i }}][credit]" type="number" min="0" value="{{ $line->credit }}" step="0.01" placeholder="Credit" class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]">
                            <input name="lines[{{ $i }}][description]" value="{{ $line->description }}" placeholder="Deskripsi" class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]">
                        </div>
                    @endforeach
                </div>
                <button type="button" class="button ghost text-sm" onclick="addJournalLine()">+ Tambah Line</button>
            </div>
            <div class="mt-4 flex items-center gap-3">
                <button class="btn">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Update Journal
                </button>
                <a class="button ghost" href="{{ route('journal-entries.index') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali
                </a>
            </div>
        
            <div class="pt-4 border-t border-[#D1D5DB] flex items-center justify-end gap-2">
                <a href="{{ route('journal-entries.index') }}" class="px-3.5 py-2 text-xs font-medium text-[#475569] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F8FAFC] transition">Batal</a>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition shadow-sm">+ Tambah Line</button>
            </div>
        </form>
    </div>
</div>
@endsection
