@extends('layouts.erp', ['activePage' => 'tax-rules', 'pageTitle' => 'Tax Rules'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#0B2345]/5" id="tax-rules">
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
        <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="field-input">
        <select name="tax_type" class="field-input">
            <option value="">Semua Tipe</option>
            <option value="PPN" @selected(request('tax_type') == 'PPN')>PPN</option>
            <option value="PPh 21" @selected(request('tax_type') == 'PPh 21')>PPh 21</option>
            <option value="PPh 23" @selected(request('tax_type') == 'PPh 23')>PPh 23</option>
            <option value="PPh 4(2)" @selected(request('tax_type') == 'PPh 4(2)')>PPh 4(2)</option>
        </select>
        <select name="is_active" class="field-input">
            <option value="">Semua Status</option>
            <option value="1" @selected(request('is_active') == '1')>Active</option>
            <option value="0" @selected(request('is_active') == '0')>Inactive</option>
        </select>
        <div class="flex gap-2 items-end">
            <button class="filter-btn">Filter</button>
            <a href="{{ route('tax-rules.index') }}" class="btn-outline">Reset</a>
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
