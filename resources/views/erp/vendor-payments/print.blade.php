@extends('layouts.pdf', [
    'docTitle'       => 'BUKTI PEMBAYARAN VENDOR (PAYMENT VOUCHER)',
    'docNumber'      => 'VP-' . str_pad($vendorPayment->id, 5, '0', STR_PAD_LEFT),
    'docDate'        => $vendorPayment->payment_date ? \Carbon\Carbon::parse($vendorPayment->payment_date)->format('d F Y') : now()->format('d F Y'),
    'docDueDate'     => '—',
    'docStatus'      => 'PAID',
    'companySetting' => $companySetting ?? null,
    'preparedBy'     => auth()->user()->name ?? 'Finance Dept',
    'reviewedBy'     => 'Accounting Manager',
    'approvedBy'     => 'Direktur Keuangan',
])

@section('content')
<table class="table-folk doc-table" style="margin-top: 0; margin-bottom: 16px;">
    <tr>
        <td style="width: 50%;">
            <div style="font-size: 8pt; color: #555; text-transform: uppercase; font-weight: bold;">Dibayarkan Kepada (Vendor):</div>
            <div style="font-size: 11pt; font-weight: bold; margin-top: 2px;">{{ $vendorPayment->vendorBill?->vendor_name ?? 'Vendor' }}</div>
            <div style="font-size: 8.5pt; color: #444; margin-top: 2px;">Nomor Tagihan: {{ $vendorPayment->vendorBill?->bill_number ?? '-' }}</div>
        </td>
        <td style="width: 50%;">
            <div style="font-size: 8pt; color: #555; text-transform: uppercase; font-weight: bold;">Rincian Pembayaran:</div>
            <div style="font-size: 9.5pt; font-weight: bold; margin-top: 2px;">Metode: Transfer Bank</div>
            <div style="font-size: 8.5pt; color: #444; margin-top: 2px;">Rekening Asal: {{ $vendorPayment->bankAccount?->name ?? 'Kas / Bank Utama' }}</div>
            @if($vendorPayment->reference)
                <div style="font-size: 8.5pt; color: #444;">No. Referensi Bank: {{ $vendorPayment->reference }}</div>
            @endif
        </td>
    </tr>
</table>

<table class="table-folk doc-table">
    <thead>
        <tr>
            <th style="width: 5%;" class="text-center">No</th>
            <th style="width: 60%;">Deskripsi Alokasi Pembayaran</th>
            <th style="width: 35%;" class="text-right">Jumlah Dibayar (IDR)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="text-center">1</td>
            <td>
                <div class="text-bold">Pelunasan Tagihan: {{ $vendorPayment->vendorBill?->bill_number ?? 'Tagihan Vendor' }}</div>
                @if($vendorPayment->vendorBill && $vendorPayment->vendorBill?->project)
                    <div style="font-size: 8pt; color: #444;">Proyek: {{ $vendorPayment->vendorBill?->project?->code }} - {{ $vendorPayment->vendorBill?->project?->name }}</div>
                @endif
            </td>
            <td class="text-right text-bold" style="font-size: 11pt;">Rp {{ number_format($vendorPayment->amount, 0, ',', '.') }}</td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" class="text-right text-bold">Total Pembayaran:</td>
            <td class="text-right text-bold" style="font-size: 11pt; color: #15803D;">Rp {{ number_format($vendorPayment->amount, 0, ',', '.') }}</td>
        </tr>
    </tfoot>
</table>
@endsection

