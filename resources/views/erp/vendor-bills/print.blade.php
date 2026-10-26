@extends('layouts.pdf', [
    'docTitle'       => 'TAGIHAN VENDOR (VENDOR BILL)',
    'docNumber'      => $vendorBill->bill_number,
    'docDate'        => $vendorBill->bill_date ? \Carbon\Carbon::parse($vendorBill->bill_date)->format('d F Y') : now()->format('d F Y'),
    'docDueDate'     => $vendorBill->due_date ? \Carbon\Carbon::parse($vendorBill->due_date)->format('d F Y') : '—',
    'docStatus'      => $vendorBill->status,
    'companySetting' => $companySetting ?? null,
    'preparedBy'     => auth()->user()->name ?? 'Procurement / Finance Dept',
    'reviewedBy'     => 'Finance Manager',
    'approvedBy'     => 'Direktur Keuangan',
])

@section('content')
@php
    $tax = (float) $vendorBill->amount * ((float) ($vendorBill->tax_rate ?? 0) / 100);
    $totalAmount = (float) $vendorBill->amount + $tax;
    $remaining = max(0, $totalAmount - (float) ($vendorBill->paid_amount ?? 0));
@endphp

<table class="table-folk doc-table" style="margin-top: 0; margin-bottom: 16px;">
    <tr>
        <td style="width: 50%;">
            <div style="font-size: 8pt; color: #555; text-transform: uppercase; font-weight: bold;">Pemasok / Vendor:</div>
            <div style="font-size: 11pt; font-weight: bold; margin-top: 2px;">{{ $vendorBill->vendor_name }}</div>
            @if($vendorBill->notes)
                <div style="font-size: 8.5pt; color: #444; margin-top: 2px;">Catatan: {{ $vendorBill->notes }}</div>
            @endif
        </td>
        <td style="width: 50%;">
            <div style="font-size: 8pt; color: #555; text-transform: uppercase; font-weight: bold;">Informasi Tagihan:</div>
            @if($vendorBill->project)
                <div style="font-size: 9.5pt; font-weight: bold; margin-top: 2px;">Proyek: {{ $vendorBill->project->code }} — {{ $vendorBill->project->name }}</div>
            @endif
            @if($vendorBill->paymentTerm)
                <div style="font-size: 8.5pt; color: #444; margin-top: 2px;">Termin: {{ $vendorBill->paymentTerm->name }} ({{ $vendorBill->paymentTerm->days }} hari)</div>
            @endif
            <div style="font-size: 8.5pt; color: #444; margin-top: 2px;">Status Pembayaran: <strong style="text-transform: uppercase;">{{ $vendorBill->status }}</strong></div>
        </td>
    </tr>
</table>

<table class="table-folk doc-table">
    <thead>
        <tr>
            <th style="width: 5%;" class="text-center">No</th>
            <th style="width: 55%;">Keterangan Pembelian / Jasa</th>
            <th style="width: 15%;" class="text-right">Tarif Pajak</th>
            <th style="width: 25%;" class="text-right">Jumlah (IDR)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="text-center">1</td>
            <td>
                <div class="text-bold">Tagihan Vendor: {{ $vendorBill->bill_number }}</div>
                <div style="font-size: 8pt; color: #444;">Vendor: {{ $vendorBill->vendor_name }}</div>
            </td>
            <td class="text-right">{{ $vendorBill->tax_rate ?? 0 }}%</td>
            <td class="text-right text-bold">Rp {{ number_format($vendorBill->amount, 0, ',', '.') }}</td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" class="text-right text-bold">Subtotal:</td>
            <td class="text-right">Rp {{ number_format($vendorBill->amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="3" class="text-right text-bold">Pajak ({{ $vendorBill->tax_rate ?? 0 }}%):</td>
            <td class="text-right">Rp {{ number_format($tax, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="3" class="text-right text-bold">Total Tagihan:</td>
            <td class="text-right text-bold" style="font-size: 11pt;">Rp {{ number_format($totalAmount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="3" class="text-right text-bold" style="color: #15803D;">Sudah Dibayar:</td>
            <td class="text-right text-bold" style="color: #15803D;">Rp {{ number_format((float)$vendorBill->paid_amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="3" class="text-right text-bold" style="color: #DC2626;">Sisa Tagihan (Hutang):</td>
            <td class="text-right text-bold" style="color: #DC2626; font-size: 11pt;">Rp {{ number_format($remaining, 0, ',', '.') }}</td>
        </tr>
    </tfoot>
</table>
@endsection
