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
    @if($reimbursementsPage->hasPages())
        <div class="pager">
            <div class="text-sm text-slate-500">{{ $reimbursementsPage->firstItem() }}–{{ $reimbursementsPage->lastItem() }} dari {{ $reimbursementsPage->total() }}</div>
            {{ $reimbursementsPage->links() }}
        </div>
    @endif
</section>
@endsection
