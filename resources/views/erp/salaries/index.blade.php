@extends('layouts.erp', ['activePage' => 'salaries', 'pageTitle' => 'Salary Workflow'])

@section('content')
<section class="card section wide">
    <div class="section-head">
        <h2>Salary Workflow</h2>
        @if($can('admin', 'hr'))
        <a class="button ghost" href="{{ route('salaries.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>
    <form method="get" action="{{ route('salaries.index-page') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <select name="status" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
            <option value="">Semua Status</option>
            <option value="draft" @selected(request('status')==='draft')>Draft</option>
            <option value="approved" @selected(request('status')==='approved')>Approved</option>
            <option value="paid" @selected(request('status')==='paid')>Paid</option>
        </select>
        <input type="text" name="period" placeholder="Cari Periode..." value="{{ request('period') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 placeholder:text-slate-400 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <select name="employee_id" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
            <option value="">Semua Karyawan</option>
            @foreach($employees as $employee)
                <option value="{{ $employee->id }}" @selected(request('employee_id')==$employee->id)>{{ $employee->name }}</option>
            @endforeach
        </select>
        <div class="flex gap-2 items-end">
            <button class="rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#003d78]">Filter</button>
            <a href="{{ route('salaries.index-page') }}" class="rounded-xl border border-[#d7e3ef] bg-white px-5 py-2.5 text-sm font-bold text-[#0059A7] hover:bg-[#f3f8fc]">Reset</a>
        </div>
    </form>
    <table>
        <thead><tr><th>Karyawan</th><th>Period</th><th>Status</th><th>Net Salary</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($salariesPage as $salary)
                <tr>
                    <td>
                        <div class="font-bold">{{ $salary->employee->name }}</div>
                        <div class="muted">{{ $salary->project?->code ?? 'Non Project' }}</div>
                    </td>
                    <td>{{ $salary->period }}</td>
                    <td>
                        @if($can('admin', 'hr', 'finance'))
                        <form method="post" action="{{ route('salaries.status', $salary) }}" class="toolbar">
                            @csrf @method('patch')
                            <select name="status" class="w-auto min-h-9 py-1.5 text-xs">
                                @foreach($statusOptions['salary'] as $status)<option @selected($salary->status===$status)>{{ $status }}</option>@endforeach
                            </select>
                            <button class="mini">Simpan</button>
                        </form>
                        @else
                            <span class="badge badge-{{ $salary->status }}">{{ $salary->status }}</span>
                        @endif
                    </td>
                    <td class="font-bold">{{ $rp($salary->net_salary) }}</td>
                    <td class="actions">
                        <a class="button mini ghost" href="{{ route('salaries.pdf', $salary) }}" target="_blank">Lihat</a>
                        @if($can('admin', 'hr'))<a class="button mini ghost" href="{{ route('salaries.edit-page', $salary) }}">Edit</a>@endif
                        @if($can('admin'))<form method="post" action="{{ route('salaries.destroy', $salary) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="py-8 text-center text-slate-500">Belum ada data salary.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($salariesPage->hasPages() || $salariesPage->total() > 0)
        <div class="pager">
            <span>Menampilkan {{ $salariesPage->firstItem() }}-{{ $salariesPage->lastItem() }} dari {{ $salariesPage->total() }}</span>
            {{ $salariesPage->links() }}
        </div>
    @endif
</section>
@endsection
