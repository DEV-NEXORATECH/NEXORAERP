@extends('layouts.erp', ['activePage' => 'sales-inquiries.index', 'pageTitle' => 'Sales Inquiries'])

@section('content')
<div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#0B2345]/5">
    <div class="section-head">
        <h2>Daftar Sales Inquiry</h2>
        @if($can('admin', 'sales'))
        <a class="button ghost" href="{{ route('sales-inquiries.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>
    <form method="get" action="{{ route('sales-inquiries.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="field-input">
        <select name="status" class="field-input">
            <option value="">Semua Status</option>
            <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New</option>
            <option value="contacted" {{ request('status') === 'contacted' ? 'selected' : '' }}>Contacted</option>
            <option value="qualified" {{ request('status') === 'qualified' ? 'selected' : '' }}>Qualified</option>
            <option value="lost" {{ request('status') === 'lost' ? 'selected' : '' }}>Lost</option>
        </select>
        <div class="flex gap-2 items-end">
            <button class="filter-btn">Filter</button>
            <a href="{{ route('sales-inquiries.index') }}" class="btn-outline">Reset</a>
        </div>
    </form>
    <table>
        <thead>
            <tr><th>Perusahaan</th><th>Kontak</th><th>Source</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($items as $row)
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
    @if($items->hasPages())
    <div class="pager">
        <span>Menampilkan {{ $items->firstItem() }}-{{ $items->lastItem() }} dari {{ $items->total() }}</span>
        {{ $items->links() }}
    </div>
    @endif
</div>
@endsection
