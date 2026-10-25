@extends('layouts.pdf', [
    'docTitle'       => 'SLIP GAJI KARYAWAN',
    'docNumber'      => $salary->slip_number,
    'docDate'        => $salary->period,
    'docStatus'      => $salary->status,
    'companySetting' => $companySetting ?? null,
    'preparedBy'     => 'HR & Payroll Dept',
    'reviewedBy'     => 'Finance Manager',
    'approvedBy'     => $salary->employee->name ?? 'Penerima Gaji',
])

@section('content')
{{-- Employee Metadata Table --}}
<table class="table-folk doc-table" style="margin-top: 0; margin-bottom: 16px;">
    <tr>
        <td style="width: 50%;">
            <div style="font-size: 8pt; color: #555; text-transform: uppercase; font-weight: bold;">Identitas Karyawan:</div>
            <div style="font-size: 11pt; font-weight: bold; margin-top: 2px;">{{ $salary->employee->name }}</div>
            <div style="font-size: 8.5pt; color: #444; margin-top: 2px;">ID Karyawan: {{ $salary->employee->employee_id ?? ('EMP-'.$salary->employee->id) }}</div>
            <div style="font-size: 8.5pt; color: #444;">Jabatan: {{ $salary->employee->jobPosition?->name ?? 'Staff' }}</div>
            <div style="font-size: 8.5pt; color: #444;">Departemen: {{ $salary->employee->departmentRecord?->name ?? 'General' }}</div>
        </td>
        <td style="width: 50%;">
            <div style="font-size: 8pt; color: #555; text-transform: uppercase; font-weight: bold;">Rincian Penggajian:</div>
            <div style="font-size: 10pt; font-weight: bold; margin-top: 2px;">Periode: {{ $salary->period }}</div>
            <div style="font-size: 8.5pt; color: #444; margin-top: 2px;">Alokasi Project: {{ $salary->project?->code ? ($salary->project->code . ' — ' . $salary->project->name) : 'Operasional Umum' }}</div>
            <div style="font-size: 8.5pt; color: #444;">Tanggal Pembayaran: {{ $salary->paid_at ? \Carbon\Carbon::parse($salary->paid_at)->format('d/m/Y') : 'Menunggu Pencairan' }}</div>
            <div style="font-size: 8.5pt; color: #444;">Status Persetujuan: <strong>{{ strtoupper($salary->status) }}</strong></div>
        </td>
    </tr>
</table>

{{-- Salary Components Breakdown Table --}}
<table class="table-folk doc-table">
    <thead>
        <tr>
            <th style="width: 5%;" class="text-center">No</th>
            <th style="width: 45%;">Komponen Pendapatan (Penghasilan)</th>
            <th style="width: 25%;" class="text-right">Nominal (IDR)</th>
            <th style="width: 25%;" class="text-right">Subtotal (IDR)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="text-center">1</td>
            <td>Gaji Pokok (Basic Salary)</td>
            <td class="text-right">Rp {{ number_format((float)$salary->base_salary, 0, ',', '.') }}</td>
            <td class="text-right text-bold">Rp {{ number_format((float)$salary->base_salary, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="text-center">2</td>
            <td>Tunjangan Tetap / Fungsional</td>
            <td class="text-right">Rp {{ number_format((float)$salary->allowances, 0, ',', '.') }}</td>
            <td class="text-right text-bold">Rp {{ number_format((float)$salary->allowances, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="text-center">3</td>
            <td>Bonus / Insentif Kinerja</td>
            <td class="text-right">Rp {{ number_format((float)$salary->bonus, 0, ',', '.') }}</td>
            <td class="text-right text-bold">Rp {{ number_format((float)$salary->bonus, 0, ',', '.') }}</td>
        </tr>
        <tr style="background-color: #F5F5F5; font-weight: bold;">
            <td colspan="3" class="text-right">TOTAL PENGHASILAN KOTOR (GROSS)</td>
            <td class="text-right">Rp {{ number_format((float)$salary->base_salary + (float)$salary->allowances + (float)$salary->bonus, 0, ',', '.') }}</td>
        </tr>
    </tbody>
</table>

{{-- Deductions Table --}}
<table class="table-folk doc-table">
    <thead>
        <tr>
            <th style="width: 5%;" class="text-center">No</th>
            <th style="width: 70%;">Komponen Potongan (Deductions)</th>
            <th style="width: 25%;" class="text-right">Nominal Potongan (IDR)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="text-center">1</td>
            <td>Potongan Pajak PPh 21 / BPJS / Keterlambatan / Pinjaman</td>
            <td class="text-right text-bold">Rp {{ number_format((float)$salary->deductions, 0, ',', '.') }}</td>
        </tr>
        <tr style="background-color: #F5F5F5; font-weight: bold;">
            <td colspan="2" class="text-right">TOTAL POTONGAN</td>
            <td class="text-right">Rp {{ number_format((float)$salary->deductions, 0, ',', '.') }}</td>
        </tr>
    </tbody>
</table>

{{-- Summary Block --}}
<div class="summary-block">
    <table class="table-folk summary-table">
        <tr class="grand-total">
            <td>PENGHASILAN BERSIH (TAKE HOME PAY)</td>
            <td class="text-right">Rp {{ number_format((float)$salary->net_salary, 0, ',', '.') }}</td>
        </tr>
    </table>
</div>

@if($salary->notes)
    <div style="margin-top: 14px; font-size: 8pt; color: #555; padding: 6px 10px; border-left: 3px solid #777;">
        Catatan Penggajian: {{ $salary->notes }}
    </div>
@endif

@endsection
