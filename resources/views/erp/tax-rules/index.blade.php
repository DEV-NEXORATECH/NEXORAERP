@extends('layouts.erp', ['activePage' => 'tax-rules', 'pageTitle' => 'Tax Rules'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5" id="tax-rules">
    <div class="section-head">
        <h2>Tax Calculation & Management</h2>
        @if($can('admin', 'finance'))
        <a href="{{ route('tax-rules.create-page') }}" class="button ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>

    <form method="get" action="{{ route('tax-rules.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 placeholder:text-slate-400 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <select name="tax_type" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
            <option value="">Semua Tipe</option>
            <option value="PPN" @selected(request('tax_type') == 'PPN')>PPN</option>
            <option value="PPh 21" @selected(request('tax_type') == 'PPh 21')>PPh 21</option>
            <option value="PPh 23" @selected(request('tax_type') == 'PPh 23')>PPh 23</option>
            <option value="PPh 4(2)" @selected(request('tax_type') == 'PPh 4(2)')>PPh 4(2)</option>
        </select>
        <select name="is_active" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
            <option value="">Semua Status</option>
            <option value="1" @selected(request('is_active') == '1')>Active</option>
            <option value="0" @selected(request('is_active') == '0')>Inactive</option>
        </select>
        <div class="flex gap-2 items-end">
            <button class="rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#003d78]">Filter</button>
            <a href="{{ route('tax-rules.index') }}" class="rounded-xl border border-[#d7e3ef] bg-white px-5 py-2.5 text-sm font-bold text-[#0059A7] hover:bg-[#f3f8fc]">Reset</a>
        </div>
    </form>

    <table>
        <thead><tr><th>Rule</th><th>Tax Type</th><th>Rate</th><th>Direction</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($taxRules as $tax)
                <tr>
                    <td class="font-bold">{{ $tax->name }}</td>
                    <td><span class="badge">{{ $tax->tax_type }}</span></td>
                    <td>{{ $tax->rate }}%</td>
                    <td>{{ $tax->direction }}</td>
                    <td><span class="badge badge-{{ $tax->is_active ? 'active' : 'void' }}">{{ $tax->is_active ? 'active' : 'inactive' }}</span></td>
                    <td class="actions">
                        @if($can('admin', 'finance'))<a class="button mini ghost" href="{{ route('tax-rules.edit-page', $tax) }}">Edit</a>@endif
                        @if($can('admin'))<form method="post" action="{{ route('tax-rules.destroy', $tax) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="py-8 text-center text-slate-500">Belum ada tax rule.</td></tr>
            @endforelse
        </tbody>
    </table>

    @if($taxRules->hasPages())
    <div class="pager">
        <span>Menampilkan {{ $taxRules->firstItem() }}-{{ $taxRules->lastItem() }} dari {{ $taxRules->total() }}</span>
        {{ $taxRules->links() }}
    </div>
    @endif
</section>
@endsection
