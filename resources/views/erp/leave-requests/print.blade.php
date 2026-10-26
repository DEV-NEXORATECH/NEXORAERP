@extends('layouts.pdf', [
    'docTitle'       => 'SURAT PERMOHONAN CUTI / IZIN KERJA',
    'docNumber'      => 'CUTI-' . str_pad($leave->id, 5, '0', STR_PAD_LEFT),
    'docDate'        => now()->format('d F Y'),
    'docDueDate'     => '—',
    'docStatus'      => $leave->status,
    'companySetting' => $companySetting ?? null,
    'preparedBy'     => $leave->employee->name ?? auth()->user()->name ?? 'Karyawan',
    'reviewedBy'     => 'Atasan Langsung',
    'approvedBy'     => 'HRD Manager',
])

@section('content')
<table class="table-folk doc-table" style="margin-top: 0; margin-bottom: 16px;">
    <tr>
        <td style="width: 50%;">
            <div style="font-size: 8pt; color: #555; text-transform: uppercase; font-weight: bold;">Identitas Karyawan:</div>
            <div style="font-size: 11pt; font-weight: bold; margin-top: 2px;">{{ $leave->employee->name ?? 'Karyawan' }}</div>
            <div style="font-size: 8.5pt; color: #444; margin-top: 2px;">Divisi: {{ $leave->employee->department->name ?? '-' }}</div>
            <div style="font-size: 8.5pt; color: #444;">Jenis Pengajuan: <strong style="text-transform: capitalize;">{{ $leave->type }}</strong></div>
        </td>
        <td style="width: 50%;">
            <div style="font-size: 8pt; color: #555; text-transform: uppercase; font-weight: bold;">Periode Cuti / Izin:</div>
            <div style="font-size: 9.5pt; font-weight: bold; margin-top: 2px;">
                {{ \Carbon\Carbon::parse($leave->start_date)->format('d F Y') }} s/d {{ \Carbon\Carbon::parse($leave->end_date)->format('d F Y') }}
            </div>
            <div style="font-size: 8.5pt; color: #444; margin-top: 2px;">Durasi Total: <strong>{{ $leave->total_days ?? 1 }} Hari Kerja</strong></div>
            <div style="font-size: 8.5pt; color: #444;">Status Persetujuan: <strong style="text-transform: uppercase;">{{ $leave->status }}</strong></div>
        </td>
    </tr>
</table>

<table class="table-folk doc-table">
    <thead>
        <tr>
            <th style="width: 25%;">Jenis Cuti</th>
            <th style="width: 50%;">Alasan Pengajuan Cuti / Izin</th>
            <th style="width: 25%;" class="text-center">Durasi Hari</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="text-bold" style="text-transform: capitalize;">{{ $leave->type }}</td>
            <td>{{ $leave->reason ?: 'Keperluan pribadi / izin cuti tahunan' }}</td>
            <td class="text-center text-bold" style="font-size: 11pt;">{{ $leave->total_days ?? 1 }} Hari</td>
        </tr>
    </tbody>
</table>
@endsection
