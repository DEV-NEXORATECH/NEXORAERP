@extends('layouts.pdf', [
    'docTitle'       => $cashflow->type === 'income' ? 'BUKTI KAS MASUK (RECEIPT VOUCHER)' : 'BUKTI KAS KELUAR (PAYMENT VOUCHER)',
    'docNumber'      => 'VCR-' . str_pad($cashflow->id, 5, '0', STR_PAD_LEFT),
    'docDate'        => $cashflow->transaction_date ? \Carbon\Carbon::parse($cashflow->transaction_date)->format('d F Y') : now()->format('d F Y'),
    'docDueDate'     => '—',
    'docStatus'      => 'POSTED',
    'companySetting' => $companySetting ?? null,
    'preparedBy'     => auth()->user()->name ?? 'Kasir / Finance',
    'reviewedBy'     => 'Accounting Supervisor',
    'approvedBy'     => 'Finance Manager',
])

@section('content')
<table class="table-folk doc-table" style="margin-top: 0; margin-bottom: 16px;">
    <tr>
        <td style="width: 50%;">
            <div style="font-size: 8pt; color: #555; text-transform: uppercase; font-weight: bold;">
                {{ $cashflow->type === 'income' ? 'Diterima Dari:' : 'Dibayarkan Kepada:' }}
            </div>
            <div style="font-size: 11pt; font-weight: bold; margin-top: 2px;">
                {{ $cashflow->vendor ?: ($cashflow->project?->client ?? 'Pihak Terkait') }}
            </div>
            <div style="font-size: 8.5pt; color: #444; margin-top: 2px;">Kategori: {{ $cashflow->category }}</div>
            @if($cashflow->cost_type)
                <div style="font-size: 8.5pt; color: #444;">Jenis Biaya: {{ ucfirst($cashflow->cost_type) }}</div>
            @endif
        </td>
        <td style="width: 50%;">
            <div style="font-size: 8pt; color: #555; text-transform: uppercase; font-weight: bold;">Informasi Rekening & Proyek:</div>
            <div style="font-size: 9.5pt; font-weight: bold; margin-top: 2px;">Bank / Kas: {{ $cashflow->bankAccount?->name ?? 'Kas Utama' }}</div>
            @if($cashflow->project)
                <div style="font-size: 8.5pt; color: #444; margin-top: 2px;">Proyek: {{ $cashflow->project?->code }} — {{ $cashflow->project?->name }}</div>
            @endif
        </td>
    </tr>
</table>

<table class="table-folk doc-table">
    <thead>
        <tr>
            <th style="width: 5%;" class="text-center">No</th>
            <th style="width: 25%;">Kategori</th>
            <th style="width: 45%;">Keterangan Transaksi</th>
            <th style="width: 25%;" class="text-right">Nominal (IDR)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="text-center">1</td>
            <td class="text-bold">{{ $cashflow->category }}</td>
            <td>{{ $cashflow->description ?: 'Transaksi ' . $cashflow->type }}</td>
            <td class="text-right text-bold" style="font-size: 11pt; {{ $cashflow->type === 'income' ? 'color: #15803D;' : 'color: #B91C1C;' }}">
                {{ $cashflow->type === 'income' ? '+' : '-' }}Rp {{ number_format($cashflow->amount, 0, ',', '.') }}
            </td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" class="text-right text-bold">Total Nilai Voucher:</td>
            <td class="text-right text-bold" style="font-size: 11pt; {{ $cashflow->type === 'income' ? 'color: #15803D;' : 'color: #B91C1C;' }}">
                Rp {{ number_format($cashflow->amount, 0, ',', '.') }}
            </td>
        </tr>
    </tfoot>
</table>
@endsection

