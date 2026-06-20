@extends('layouts.erp', ['activePage' => 'sales-targets.index', 'pageTitle' => 'Sales Targets'])

@section('content')
<section class="card section wide">
    <div class="section-head">
        <h2>Sales Targets</h2>
        @if($can('admin', 'sales'))
        <a class="button ghost" href="{{ route('sales-targets.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>
    <table>
        <thead>
            <tr><th>User</th><th>Period</th><th>Target</th><th>Achieved</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($targets as $row)
                <tr>
                    <td class="font-bold">{{ $row->user->name ?? '-' }}</td>
                    <td>{{ $row->period }}</td>
                    <td class="font-bold">{{ $rp($row->target_amount) }}</td>
                    <td class="font-bold">{{ $rp($row->achieved_amount) }}</td>
                    <td class="actions">
                        @if($can('admin', 'sales'))<a class="button mini ghost" href="{{ route('sales-targets.edit-page', $row) }}">Edit</a>@endif
                        @if($can('admin', 'sales'))<form method="post" action="{{ route('sales-targets.destroy', $row) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="py-8 text-center text-slate-500">Belum ada target.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($targets->hasPages())
        <div class="pager">
            <div class="text-sm text-slate-500">Menampilkan {{ $targets->firstItem() }}–{{ $targets->lastItem() }} dari {{ $targets->total() }}</div>
            {{ $targets->links() }}
        </div>
    @endif
</section>
@endsection
