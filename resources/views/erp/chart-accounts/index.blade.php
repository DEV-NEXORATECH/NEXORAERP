@extends('layouts.erp', ['activePage' => 'chart-accounts', 'pageTitle' => 'Chart of Accounts'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5" id="chart-accounts">
    <div class="section-head">
        <h2>Chart of Accounts</h2>
        @if($can('admin', 'finance'))
        <a class="button ghost" href="{{ route('chart-accounts.create-page') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>

    <form method="get" action="{{ route('chart-accounts.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 placeholder:text-slate-400 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <select name="type" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
            <option value="">Semua Tipe</option>
            <option value="asset" @selected(request('type') == 'asset')>Asset</option>
            <option value="liability" @selected(request('type') == 'liability')>Liability</option>
            <option value="equity" @selected(request('type') == 'equity')>Equity</option>
            <option value="income" @selected(request('type') == 'income')>Income</option>
            <option value="expense" @selected(request('type') == 'expense')>Expense</option>
        </select>
        <select name="is_active" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
            <option value="">Semua Status</option>
            <option value="1" @selected(request('is_active') == '1')>Active</option>
            <option value="0" @selected(request('is_active') == '0')>Inactive</option>
        </select>
        <div class="flex gap-2 items-end">
            <button class="rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#003d78]">Filter</button>
            <a href="{{ route('chart-accounts.index') }}" class="rounded-xl border border-[#d7e3ef] bg-white px-5 py-2.5 text-sm font-bold text-[#0059A7] hover:bg-[#f3f8fc]">Reset</a>
        </div>
    </form>

    <table>
        <thead><tr><th>Code</th><th>Name</th><th>Type</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($accounts as $account)
                <tr>
                    <td class="font-bold">{{ $account->code }}</td>
                    <td>{{ $account->name }}<br><span class="muted">{{ $account->parent?->code ? $account->parent->code.' - '.$account->parent->name : 'Root' }}</span></td>
                    <td><span class="badge">{{ $account->type }}</span></td>
                    <td><span class="badge badge-{{ $account->is_active ? 'active' : 'void' }}">{{ $account->is_active ? 'active' : 'inactive' }}</span></td>
                    <td class="actions">
                        @if($can('admin', 'finance'))<a class="button mini ghost" href="{{ route('chart-accounts.edit-page', $account) }}">Edit</a>@endif
                        @if($can('admin'))<form method="post" action="{{ route('chart-accounts.destroy', $account) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="py-8 text-center text-slate-500">Belum ada CoA.</td></tr>
            @endforelse
        </tbody>
    </table>

    @if($accounts->hasPages())
    <div class="pager">
        <span>Menampilkan {{ $accounts->firstItem() }}-{{ $accounts->lastItem() }} dari {{ $accounts->total() }}</span>
        {{ $accounts->links() }}
    </div>
    @endif
</section>
@endsection
