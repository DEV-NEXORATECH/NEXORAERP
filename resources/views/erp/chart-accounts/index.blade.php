@extends('layouts.erp', ['activePage' => 'chart-accounts', 'pageTitle' => 'Chart of Accounts'])

@section('content')
<section class="card section wide">
    <div class="section-head">
        <h2>Chart of Accounts</h2>
        @if($can('admin', 'finance'))
        <a class="button ghost" href="{{ route('chart-accounts.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>
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
            <div class="text-sm text-slate-500">{{ $accounts->firstItem() }}–{{ $accounts->lastItem() }} dari {{ $accounts->total() }}</div>
            {{ $accounts->links() }}
        </div>
    @endif
</section>
@endsection
