@extends('layouts.pdf', [
    'docTitle'       => 'FORMULIR KLAIM REIMBURSEMENT',
    'docNumber'      => 'RMB-' . str_pad($reimbursement->id, 5, '0', STR_PAD_LEFT),
    'docDate'        => $reimbursement->expense_date ? \Carbon\Carbon::parse($reimbursement->expense_date)->format('d F Y') : now()->format('d F Y'),
    'docDueDate'     => '—',
    'docStatus'      => $reimbursement->status,
    'companySetting' => $companySetting ?? null,
    'preparedBy'     => $reimbursement->employee?->name ?? auth()->user()->name ?? 'Karyawan',
    'reviewedBy'     => 'HR / Finance Manager',
    'approvedBy'     => 'Direktur Operasional',
])

@section('content')
<table class="table-folk doc-table" style="margin-top: 0; margin-bottom: 16px;">
    <tr>
        <td style="width: 50%;">
            <div style="font-size: 8pt; color: #555; text-transform: uppercase; font-weight: bold;">Karyawan Pemohon (Klaim):</div>
            <div style="font-size: 11pt; font-weight: bold; margin-top: 2px;">{{ $reimbursement->employee?->name ?? 'Karyawan' }}</div>
            <div style="font-size: 8.5pt; color: #444; margin-top: 2px;">NIK/Kode: {{ $reimbursement->employee?->employee_code ?? '-' }}</div>
            <div style="font-size: 8.5pt; color: #444;">Divisi / Jabatan: {{ $reimbursement->employee?->departmentRecord->name ?? '-' }}</div>
        </td>
        <td style="width: 50%;">
            <div style="font-size: 8pt; color: #555; text-transform: uppercase; font-weight: bold;">Informasi Pengeluaran:</div>
            <div style="font-size: 9.5pt; font-weight: bold; margin-top: 2px;">Kategori: {{ $reimbursement->category }}</div>
            @if($reimbursement->project)
                <div style="font-size: 8.5pt; color: #444; margin-top: 2px;">Beban Proyek: {{ $reimbursement->project?->code }} — {{ $reimbursement->project?->name }}</div>
            @endif
            <div style="font-size: 8.5pt; color: #444; margin-top: 2px;">Status Klaim: <strong style="text-transform: uppercase;">{{ $reimbursement->status }}</strong></div>
        </td>
    </tr>
</table>

<table class="table-folk doc-table">
    <thead>
        <tr>
            <th style="width: 5%;" class="text-center">No</th>
            <th style="width: 25%;">Kategori</th>
            <th style="width: 45%;">Deskripsi Pengeluaran</th>
            <th style="width: 25%;" class="text-right">Nominal (IDR)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="text-center">1</td>
            <td class="text-bold">{{ $reimbursement->category }}</td>
            <td>
                <div>{{ $reimbursement->description ?: 'Pengeluaran operasional dinas' }}</div>
                @if($reimbursement->receipt_file_path)
                    <div style="font-size: 8pt; color: #15803D; margin-top: 2px;">✓ Bukti struk/nota terlampir</div>
                @endif
            </td>
            <td class="text-right text-bold" style="font-size: 11pt;">Rp {{ number_format($reimbursement->amount, 0, ',', '.') }}</td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" class="text-right text-bold">Total Nilai Klaim:</td>
            <td class="text-right text-bold" style="font-size: 11pt; color: #15803D;">Rp {{ number_format($reimbursement->amount, 0, ',', '.') }}</td>
        </tr>
    </tfoot>
</table>
@endsection

