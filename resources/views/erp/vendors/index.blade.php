@extends('layouts.erp', ['activePage' => 'vendors', 'pageTitle' => 'Vendor / Supplier Management'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5" id="vendors">
    <div class="section-head">
        <h2>Vendor / Supplier Management</h2>
        <a href="{{ route('vendors.create-page') }}" class="button ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
            Tambah Vendor
        </a>
    </div>

    <form method="get" action="{{ route('vendors.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 placeholder:text-slate-400 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <select name="status" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
            <option value="">Semua Status</option>
            <option value="active" @selected(request('status') == 'active')>Active</option>
            <option value="inactive" @selected(request('status') == 'inactive')>Inactive</option>
            <option value="blacklisted" @selected(request('status') == 'blacklisted')>Blacklisted</option>
        </select>
        <div class="flex gap-2 items-end">
            <button class="rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#003d78]">Filter</button>
            <a href="{{ route('vendors.index') }}" class="rounded-xl border border-[#d7e3ef] bg-white px-5 py-2.5 text-sm font-bold text-[#0059A7] hover:bg-[#f3f8fc]">Reset</a>
        </div>
    </form>

    <table>
        <thead>
            <tr><th>Vendor</th><th>Kontak</th><th>Terms</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($vendors as $row)
                <tr>
                    <td class="font-bold">{{ $row->name }}<br><span class="muted">{{ $row->category }}</span></td>
                    <td>{{ $row->contact_name ?? '-' }}<br><span class="muted">{{ $row->email ?? $row->phone ?? '' }}</span></td>
                    <td>{{ $row->payment_terms ?? '-' }}</td>
                    <td><span class="badge badge-{{ $row->status === 'active' ? 'active' : 'void' }}">{{ $row->status }}</span></td>
                    <td class="actions">
                        <a class="button mini ghost" href="{{ route('vendors.edit-page', $row) }}">Edit</a>
                        <form method="post" action="{{ route('vendors.destroy', $row) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="py-8 text-center text-slate-500">Belum ada vendor.</td></tr>
            @endforelse
        </tbody>
    </table>

    @if($vendors->hasPages())
    <div class="pager">
        <span>Menampilkan {{ $vendors->firstItem() }}-{{ $vendors->lastItem() }} dari {{ $vendors->total() }}</span>
        {{ $vendors->links() }}
    </div>
    @endif
</section>
@endsection
