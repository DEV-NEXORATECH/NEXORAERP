@extends('layouts.erp', ['activePage' => 'sales-commissions.index', 'pageTitle' => 'Sales Commissions'])

@section('content')
<section class="card section wide">
    <div class="section-head">
        <h2>Sales Commissions</h2>
        @if($can('admin', 'sales'))
        <a class="button ghost" href="{{ route('sales-commissions.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>
    <table>
        <thead>
            <tr><th>User</th><th>Period</th><th>Base Amount</th><th>Rate</th><th>Commission</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($commissions as $row)
                <tr>
                    <td class="font-bold">{{ $row->user->name ?? '-' }}</td>
                    <td>{{ $row->period }}</td>
                    <td>{{ $rp($row->base_amount) }}</td>
                    <td>{{ $row->rate }}%</td>
                    <td class="font-bold">{{ $rp($row->commission_amount) }}</td>
                    <td><span class="badge badge-{{ $row->status === 'paid' ? 'active' : ($row->status === 'draft' ? 'pending' : 'void') }}">{{ $row->status }}</span></td>
                    <td class="actions">
                        @if($can('admin', 'sales'))<a class="button mini ghost" href="{{ route('sales-commissions.edit-page', $row) }}">Edit</a>@endif
                        @if($can('admin', 'sales'))<form method="post" action="{{ route('sales-commissions.destroy', $row) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="py-8 text-center text-slate-500">Belum ada komisi.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($commissions->hasPages())
        <div class="pager">
            <div class="text-sm text-slate-500">Menampilkan {{ $commissions->firstItem() }}–{{ $commissions->lastItem() }} dari {{ $commissions->total() }}</div>
            {{ $commissions->links() }}
        </div>
    @endif
</section>
@endsection
