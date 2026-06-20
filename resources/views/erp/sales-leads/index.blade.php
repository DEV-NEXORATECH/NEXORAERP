@extends('layouts.erp', ['activePage' => 'sales-leads.index', 'pageTitle' => 'Sales Leads Pipeline'])

@section('content')
<section class="card section wide">
    <div class="section-head">
        <h2>Sales Leads Pipeline</h2>
        @if($can('admin', 'sales'))
        <a class="button ghost" href="{{ route('sales-leads.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>
    <table>
        <thead>
            <tr><th>Title</th><th>Stage</th><th>Value</th><th>Prob.</th><th>Expected Close</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($leads as $row)
                <tr>
                    <td class="font-bold">{{ $row->title }}</td>
                    <td><span class="badge badge-{{ $row->stage === 'won' ? 'active' : ($row->stage === 'lost' ? 'void' : 'pending') }}">{{ $row->stage }}</span></td>
                    <td class="font-bold">{{ $rp($row->value) }}</td>
                    <td>{{ $row->probability }}%</td>
                    <td>{{ $row->expected_close_date ? \Carbon\Carbon::parse($row->expected_close_date)->format('d M Y') : '-' }}</td>
                    <td class="actions">
                        @if($can('admin', 'sales'))<a class="button mini ghost" href="{{ route('sales-leads.edit-page', $row) }}">Edit</a>@endif
                        @if($can('admin', 'sales'))<form method="post" action="{{ route('sales-leads.destroy', $row) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="py-8 text-center text-slate-500">Belum ada lead.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($leads->hasPages())
        <div class="pager">
            <div class="text-sm text-slate-500">Menampilkan {{ $leads->firstItem() }}–{{ $leads->lastItem() }} dari {{ $leads->total() }}</div>
            {{ $leads->links() }}
        </div>
    @endif
</section>
@endsection
