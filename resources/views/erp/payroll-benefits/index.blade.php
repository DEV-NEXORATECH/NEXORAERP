@extends('layouts.erp', ['activePage' => 'payroll-benefits', 'pageTitle' => 'Payroll Benefits'])

@section('content')
<div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
    <div class="section-head">
        <h2>Daftar Payroll Benefit</h2>
        @if($can('admin', 'hr'))
        <a class="button ghost" href="{{ route('payroll-benefits.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>
    <form method="get" action="{{ route('payroll-benefits.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 placeholder:text-slate-400 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <select name="employee_id" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
            <option value="">Semua Karyawan</option>
            @foreach($employees as $emp)
                <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
            @endforeach
        </select>
        <div class="flex gap-2 items-end">
            <button class="rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#003d78]">Filter</button>
            <a href="{{ route('payroll-benefits.index') }}" class="rounded-xl border border-[#d7e3ef] bg-white px-5 py-2.5 text-sm font-bold text-[#0059A7] hover:bg-[#f3f8fc]">Reset</a>
        </div>
    </form>
    <table>
        <thead><tr><th>Karyawan</th><th>Period</th><th>BPJS Kes</th><th>BPJS TK</th><th>PPH21</th><th>Insentif</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($items as $row)
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
    @if($items->hasPages())
    <div class="pager">
        <span>Menampilkan {{ $items->firstItem() }}-{{ $items->lastItem() }} dari {{ $items->total() }}</span>
        {{ $items->links() }}
    </div>
    @endif
</div>
@endsection
