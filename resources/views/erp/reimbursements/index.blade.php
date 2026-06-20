@extends('layouts.erp', ['activePage' => 'reimbursements', 'pageTitle' => 'Reimbursement Workflow'])

@section('content')
<section class="card section wide">
    <div class="section-head">
        <h2>Reimbursement Workflow</h2>
        @if($can('admin', 'hr', 'finance'))
        <a class="button ghost" href="{{ route('reimbursements.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>
    <form method="get" action="{{ route('reimbursements.index-page') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <select name="status" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
            <option value="">Semua Status</option>
            <option value="pending" @selected(request('status')==='pending')>Pending</option>
            <option value="approved" @selected(request('status')==='approved')>Approved</option>
            <option value="paid" @selected(request('status')==='paid')>Paid</option>
            <option value="rejected" @selected(request('status')==='rejected')>Rejected</option>
        </select>
        <select name="employee_id" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
            <option value="">Semua Karyawan</option>
            @foreach($employees as $employee)
                <option value="{{ $employee->id }}" @selected(request('employee_id')==$employee->id)>{{ $employee->name }}</option>
            @endforeach
        </select>
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <div class="flex gap-2 items-end">
            <button class="rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#003d78]">Filter</button>
            <a href="{{ route('reimbursements.index-page') }}" class="rounded-xl border border-[#d7e3ef] bg-white px-5 py-2.5 text-sm font-bold text-[#0059A7] hover:bg-[#f3f8fc]">Reset</a>
        </div>
    </form>
    <table>
        <thead><tr><th>Karyawan</th><th>Kategori</th><th>Status</th><th>Amount</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($reimbursementsPage as $reimbursement)
                <tr>
                    <td>
                        <div class="font-bold">{{ $reimbursement->employee->name }}</div>
                        <div class="muted">{{ $reimbursement->project?->code ?? 'Non Project' }}</div>
                    </td>
                    <td>{{ $reimbursement->category }}</td>
                    <td>
                        @if($can('admin', 'hr', 'finance'))
                        <form method="post" action="{{ route('reimbursements.status', $reimbursement) }}" class="toolbar">
                            @csrf @method('patch')
                            <select name="status" class="w-auto min-h-9 py-1.5 text-xs">
                                @foreach($statusOptions['reimbursement'] as $status)<option @selected($reimbursement->status===$status)>{{ $status }}</option>@endforeach
                            </select>
                            <button class="mini">Simpan</button>
                        </form>
                        @else
                            <span class="badge badge-{{ $reimbursement->status }}">{{ $reimbursement->status }}</span>
                        @endif
                    </td>
                    <td class="font-bold">{{ $rp($reimbursement->amount) }}</td>
                    <td class="actions">
                        @if($can('admin', 'hr', 'finance'))<a class="button mini ghost" href="{{ route('reimbursements.edit-page', $reimbursement) }}">Edit</a>@endif
                        @if($can('admin'))<form method="post" action="{{ route('reimbursements.destroy', $reimbursement) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="py-8 text-center text-slate-500">Belum ada data reimbursement.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($reimbursementsPage->hasPages() || $reimbursementsPage->total() > 0)
        <div class="pager">
            <span>Menampilkan {{ $reimbursementsPage->firstItem() }}-{{ $reimbursementsPage->lastItem() }} dari {{ $reimbursementsPage->total() }}</span>
            {{ $reimbursementsPage->links() }}
        </div>
    @endif
</section>
@endsection
