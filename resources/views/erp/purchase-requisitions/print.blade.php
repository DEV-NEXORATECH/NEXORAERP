@extends('layouts.pdf', [
    'docTitle'       => 'PERMINTAAN PEMBELIAN (PURCHASE REQUISITION)',
    'docNumber'      => $requisition->number,
    'docDate'        => now()->format('d F Y'),
    'docDueDate'     => $requisition->required_date ? \Carbon\Carbon::parse($requisition->required_date)->format('d F Y') : '—',
    'docStatus'      => $requisition->status,
    'companySetting' => $companySetting ?? null,
    'preparedBy'     => $requisition->requester?->name ?? auth()->user()->name ?? 'Pemohon',
    'reviewedBy'     => 'Kepala Divisi',
    'approvedBy'     => 'Manajer Pengadaan',
])

@section('content')
<table class="table-folk doc-table" style="margin-top: 0; margin-bottom: 16px;">
    <tr>
        <td style="width: 50%;">
            <div style="font-size: 8pt; color: #555; text-transform: uppercase; font-weight: bold;">Pemohon Pengadaan:</div>
            <div style="font-size: 11pt; font-weight: bold; margin-top: 2px;">{{ $requisition->requester?->name ?? 'Karyawan' }}</div>
            <div style="font-size: 8.5pt; color: #444; margin-top: 2px;">Divisi: {{ $requisition->department?->name ?? 'Umum' }}</div>
        </td>
        <td style="width: 50%;">
            <div style="font-size: 8pt; color: #555; text-transform: uppercase; font-weight: bold;">Kebutuhan & Jadwal:</div>
            <div style="font-size: 9.5pt; font-weight: bold; margin-top: 2px;">Target Diperlukan: {{ $requisition->required_date ? \Carbon\Carbon::parse($requisition->required_date)->format('d F Y') : 'Secepatnya' }}</div>
            <div style="font-size: 8.5pt; color: #444; margin-top: 2px;">Status Persetujuan: <strong style="text-transform: uppercase;">{{ $requisition->status }}</strong></div>
        </td>
    </tr>
</table>

<table class="table-folk doc-table">
    <thead>
        <tr>
            <th style="width: 5%;" class="text-center">No</th>
            <th style="width: 45%;">Judul Kebutuhan Pengadaan</th>
            <th style="width: 25%;">Alasan / Justifikasi</th>
            <th style="width: 25%;" class="text-right">Estimasi Anggaran (IDR)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="text-center">1</td>
            <td>
                <div class="text-bold">{{ $requisition->title }}</div>
            </td>
            <td>{{ $requisition->reason ?: 'Kebutuhan operasional kantor / proyek' }}</td>
            <td class="text-right text-bold" style="font-size: 11pt;">Rp {{ number_format($requisition->amount, 0, ',', '.') }}</td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" class="text-right text-bold">Total Estimasi Anggaran:</td>
            <td class="text-right text-bold" style="font-size: 11pt; color: #002875;">Rp {{ number_format($requisition->amount, 0, ',', '.') }}</td>
        </tr>
    </tfoot>
</table>
@endsection

