@extends('layouts.erp', ['activePage' => 'payroll-benefits', 'pageTitle' => 'Payroll Benefits'])

@section('content')
<section class="card section wide">
    <div class="section-head">
        <h2>Payroll Benefits</h2>
        @if($can('admin', 'hr'))
        <a class="button ghost" href="{{ route('payroll-benefits.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>
    <table>
        <thead><tr><th>Karyawan</th><th>Period</th><th>BPJS Kes</th><th>BPJS TK</th><th>PPH21</th><th>Insentif</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($benefits as $row)
                <tr>
                    <td class="font-bold">{{ $row->employee->name }}</td>
                    <td>{{ $row->period }}</td>
                    <td>{{ $rp($row->bpjs_health ?? 0) }}</td>
                    <td>{{ $rp($row->bpjs_employment ?? 0) }}</td>
                    <td>{{ $rp($row->pph21 ?? 0) }}</td>
                    <td>{{ $rp($row->incentive ?? 0) }}</td>
                    <td><span class="badge badge-{{ $row->status }}">{{ $row->status }}</span></td>
                    <td class="actions">
                        @if($can('admin', 'hr'))<a class="button mini ghost" href="{{ route('payroll-benefits.edit-page', $row) }}">Edit</a>@endif
                        @if($can('admin'))<form method="post" action="{{ route('payroll-benefits.destroy', $row) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="py-8 text-center text-slate-500">Belum ada data benefit.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($benefits->hasPages())
        <div class="pager">
            <div class="text-sm text-slate-500">Menampilkan {{ $benefits->firstItem() }}–{{ $benefits->lastItem() }} dari {{ $benefits->total() }}</div>
            {{ $benefits->links() }}
        </div>
    @endif
</section>
@endsection
