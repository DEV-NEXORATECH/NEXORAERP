@extends('layouts.pdf', [
    'docTitle'       => 'INVOICE TAGIHAN',
    'docNumber'      => $invoice->number,
    'docDate'        => $invoice->issue_date,
    'docDueDate'     => $invoice->due_date ?? '—',
    'docStatus'      => $invoice->status,
    'companySetting' => $companySetting ?? null,
    'preparedBy'     => auth()->user()->name ?? 'Finance Dept',
    'reviewedBy'     => 'Finance Manager',
    'approvedBy'     => 'Direktur Utama',
])

@section('content')
@php
    $tax        = (float) $invoice->amount * ((float) $invoice->tax_rate / 100);
    $grandTotal = (float) $invoice->amount + $tax;
    $remaining  = max(0, $grandTotal - (float) $invoice->paid_amount);
@endphp

{{-- Client & Project Info Table --}}
<table class="doc-table" style="margin-top: 0; margin-bottom: 16px;">
    <tr>
        <td style="width: 50%;">
            <div style="font-size: 8pt; color: #555; text-transform: uppercase; font-weight: bold;">Ditagihkan Kepada (Bill To):</div>
            <div style="font-size: 11pt; font-weight: bold; margin-top: 2px;">{{ $invoice->project->client ?? ($invoice->project->clientRecord->name ?? 'Pelanggan Umum') }}</div>
            @if($invoice->project->clientRecord && $invoice->project->clientRecord->address)
                <div style="font-size: 8.5pt; color: #444; margin-top: 2px;">{{ $invoice->project->clientRecord->address }}</div>
            @endif
            @if($invoice->project->clientRecord && ($invoice->project->clientRecord->email || $invoice->project->clientRecord->phone))
                <div style="font-size: 8.5pt; color: #444;">{{ $invoice->project->clientRecord->email }} | {{ $invoice->project->clientRecord->phone }}</div>
            @endif
        </td>
        <td style="width: 50%;">
            <div style="font-size: 8pt; color: #555; text-transform: uppercase; font-weight: bold;">Referensi Proyek:</div>
            <div style="font-size: 10pt; font-weight: bold; margin-top: 2px;">{{ $invoice->project->code }} — {{ $invoice->project->name }}</div>
            <div style="font-size: 8.5pt; color: #444; margin-top: 2px;">Nilai Kontrak: Rp {{ number_format((float)$invoice->project->contract_value, 0, ',', '.') }}</div>
            @if($invoice->paymentTerm)
                <div style="font-size: 8.5pt; color: #444;">Syarat Pembayaran: {{ $invoice->paymentTerm->name }} ({{ $invoice->paymentTerm->days }} hari)</div>
            @endif
        </td>
    </tr>
</table>

{{-- Line Items Table --}}
<table class="doc-table">
    <thead>
        <tr>
            <th style="width: 5%;" class="text-center">No</th>
            <th style="width: 50%;">Deskripsi Pekerjaan / Layanan</th>
            <th style="width: 15%;" class="text-right">Tarif Pajak</th>
            <th style="width: 30%;" class="text-right">Jumlah (IDR)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="text-center">1</td>
            <td>
                <div class="text-bold">{{ $invoice->project->name }} (Tahap Tagihan Invoice {{ $invoice->number }})</div>
                @if($invoice->notes)
                    <div style="font-size: 8pt; color: #444; margin-top: 3px;">Catatan: {{ $invoice->notes }}</div>
                @endif
            </td>
            <td class="text-right">{{ number_format((float)$invoice->tax_rate, 1) }}%</td>
            <td class="text-right text-bold">Rp {{ number_format((float)$invoice->amount, 0, ',', '.') }}</td>
        </tr>
    </tbody>
</table>

{{-- Summary Block --}}
<div class="summary-block">
    <table class="summary-table">
        <tr>
            <td>Subtotal Tagihan</td>
            <td class="text-right text-bold">Rp {{ number_format((float)$invoice->amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>PPN / Pajak ({{ number_format((float)$invoice->tax_rate, 1) }}%)</td>
            <td class="text-right">Rp {{ number_format($tax, 0, ',', '.') }}</td>
        </tr>
        <tr class="grand-total">
            <td>TOTAL TAGIHAN</td>
            <td class="text-right">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Telah Dibayar (Paid)</td>
            <td class="text-right">Rp {{ number_format((float)$invoice->paid_amount, 0, ',', '.') }}</td>
        </tr>
        <tr style="font-weight: bold; background-color: #F8F8F8;">
            <td>Sisa Tagihan (Outstanding)</td>
            <td class="text-right" style="color: #000000;">Rp {{ number_format($remaining, 0, ',', '.') }}</td>
        </tr>
    </table>
</div>

{{-- Payment Instructions --}}
@php
    $bank = $companySetting->defaultBankAccount ?? \App\Models\BankAccount::first();
@endphp
@if($bank)
    <div style="margin-top: 18px; padding: 10px 14px; border: 1px solid #777; background-color: #FAFAFA; font-size: 8.5pt;">
        <div style="font-weight: bold; text-transform: uppercase; margin-bottom: 2px;">Instruksi Pembayaran Transfer Bank:</div>
        <div>Bank: <strong>{{ $bank->bank_name }}</strong> | No. Rekening: <strong>{{ $bank->account_number }}</strong> | Atas Nama: <strong>{{ $bank->account_name }}</strong></div>
        <div style="color: #555; font-size: 8pt; margin-top: 2px;">Harap mencantumkan nomor invoice <strong>{{ $invoice->number }}</strong> pada berita transfer pembayaran Anda.</div>
    </div>
@endif

@endsection
