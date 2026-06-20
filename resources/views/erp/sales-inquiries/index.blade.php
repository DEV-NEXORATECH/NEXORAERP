@extends('layouts.erp', ['activePage' => 'sales-inquiries.index', 'pageTitle' => 'Sales Inquiries'])

@section('content')
<section class="card section wide">
    <div class="section-head">
        <h2>Sales Inquiries</h2>
        @if($can('admin', 'sales'))
        <a class="button ghost" href="{{ route('sales-inquiries.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>
    <table>
        <thead>
            <tr><th>Perusahaan</th><th>Kontak</th><th>Source</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($inquiries as $row)
                <tr>
                    <td class="font-bold">{{ $row->company_name }}<br><span class="muted">{{ $row->need }}</span></td>
                    <td>{{ $row->contact_name ?? '-' }}<br><span class="muted">{{ $row->email ?? $row->phone ?? '' }}</span></td>
                    <td>{{ $row->source ?? '-' }}</td>
                    <td><span class="badge badge-{{ $row->status === 'lost' ? 'void' : ($row->status === 'qualified' ? 'active' : 'pending') }}">{{ $row->status }}</span></td>
                    <td class="actions">
                        @if($can('admin', 'sales'))<a class="button mini ghost" href="{{ route('sales-inquiries.edit-page', $row) }}">Edit</a>@endif
                        @if($can('admin', 'sales'))<form method="post" action="{{ route('sales-inquiries.destroy', $row) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="py-8 text-center text-slate-500">Belum ada inquiry.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($inquiries->hasPages())
        <div class="pager">
            <div class="text-sm text-slate-500">Menampilkan {{ $inquiries->firstItem() }}–{{ $inquiries->lastItem() }} dari {{ $inquiries->total() }}</div>
            {{ $inquiries->links() }}
        </div>
    @endif
</section>
@endsection
