@extends('layouts.erp', ['activePage' => 'bank-accounts', 'pageTitle' => 'Bank Accounts'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5" id="bank-accounts">
    <div class="section-head">
        <h2>Daftar Bank Account</h2>
        @if($can('admin', 'finance') || $can('admin'))
        <a href="{{ route('bank-accounts.create-page') }}" class="button ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>

    <form method="get" action="{{ route('bank-accounts.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 placeholder:text-slate-400 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <div class="flex gap-2 items-end">
            <button class="rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#003d78]">Filter</button>
            <a href="{{ route('bank-accounts.index') }}" class="rounded-xl border border-[#d7e3ef] bg-white px-5 py-2.5 text-sm font-bold text-[#0059A7] hover:bg-[#f3f8fc]">Reset</a>
        </div>
    </form>

    <table>
        <thead><tr><th>Nama</th><th>Bank</th><th>No. Rekening</th><th>Saldo Awal</th><th>Aksi</th></tr></thead>
        <tbody>
            @foreach($bankAccounts as $bank)
            <tr>
                <td class="font-bold">{{ $bank->name }}</td>
                <td>{{ $bank->bank_name }}</td>
                <td>{{ $bank->account_number }}</td>
                <td>{{ $rp($bank->opening_balance) }}</td>
                <td class="actions">
                    <a href="{{ route('bank-accounts.edit-page', $bank) }}" class="mini ghost">Edit</a>
                    <form method="post" action="{{ route('bank-accounts.destroy', $bank) }}" class="inline">@csrf @method('delete')<button class="mini danger">Delete</button></form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($bankAccounts->hasPages())
    <div class="pager">
        <span>Menampilkan {{ $bankAccounts->firstItem() }}-{{ $bankAccounts->lastItem() }} dari {{ $bankAccounts->total() }}</span>
        {{ $bankAccounts->links() }}
    </div>
    @endif
</section>
@endsection
