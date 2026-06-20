@extends('layouts.erp', ['activePage' => 'bank-reconciliation-items.index', 'pageTitle' => 'Bank Reconciliation'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5" id="bank-reconciliation-items">
    <div class="section-head">
        <h2>Bank Reconciliation Items</h2>
        @if($can('admin', 'finance'))
        <a href="{{ route('bank-reconciliation-items.create-page') }}" class="button ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>

    <form method="get" action="{{ route('bank-reconciliation-items.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        @if(isset($banks))
        <select name="bank_account_id" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
            <option value="">Semua Bank</option>
            @foreach($banks as $bank)
            <option value="{{ $bank->id }}" @selected(request('bank_account_id') == $bank->id)>{{ $bank->name }}</option>
            @endforeach
        </select>
        @endif
        <select name="reconciled" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
            <option value="">Semua Status</option>
            <option value="1" @selected(request('reconciled') == '1')>Reconciled</option>
            <option value="0" @selected(request('reconciled') == '0')>Unreconciled</option>
        </select>
        <div class="flex gap-2 items-end">
            <button class="rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#003d78]">Filter</button>
            <a href="{{ route('bank-reconciliation-items.index') }}" class="rounded-xl border border-[#d7e3ef] bg-white px-5 py-2.5 text-sm font-bold text-[#0059A7] hover:bg-[#f3f8fc]">Reset</a>
        </div>
    </form>

    <table>
        <thead>
            <tr><th>Bank</th><th>Tanggal</th><th>Deskripsi</th><th>Amount</th><th>Tipe</th><th>Reconciled</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($reconciliations as $row)
                <tr>
                    <td class="font-bold">{{ $row->bankAccount?->name ?? '-' }}</td>
                    <td>{{ $row->statement_date }}</td>
                    <td>{{ $row->description ?? $row->reference ?? '-' }}<br><span class="muted">{{ $row->reference ?? '' }}</span></td>
                    <td>{{ $rp($row->amount) }}</td>
                    <td><span class="badge badge-{{ $row->type === 'credit' ? 'active' : 'pending' }}">{{ $row->type }}</span></td>
                    <td><span class="badge badge-{{ $row->reconciled ? 'active' : 'void' }}">{{ $row->reconciled ? 'Yes' : 'No' }}</span></td>
                    <td class="actions">
                        @if($can('admin', 'finance'))<a class="button mini ghost" href="{{ route('bank-reconciliation-items.edit-page', $row) }}">Edit</a>@endif
                        @if($can('admin', 'finance'))<form method="post" action="{{ route('bank-reconciliation-items.destroy', $row) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="py-8 text-center text-slate-500">Belum ada reconciliation item.</td></tr>
            @endforelse
        </tbody>
    </table>

    @if($reconciliations->hasPages())
    <div class="pager">
        <span>Menampilkan {{ $reconciliations->firstItem() }}-{{ $reconciliations->lastItem() }} dari {{ $reconciliations->total() }}</span>
        {{ $reconciliations->links() }}
    </div>
    @endif
</section>
@endsection
