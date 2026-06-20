@extends('layouts.erp', ['activePage' => 'tax-rules', 'pageTitle' => 'Tax Rules'])

@section('content')
<section class="card section wide">
    <div class="section-head">
        <h2>Tax Calculation & Management</h2>
        @if($can('admin', 'finance'))
        <a class="button ghost" href="{{ route('tax-rules.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>
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
            <div class="text-sm text-slate-500">{{ $taxRules->firstItem() }}–{{ $taxRules->lastItem() }} dari {{ $taxRules->total() }}</div>
            {{ $taxRules->links() }}
        </div>
    @endif
</section>
@endsection
