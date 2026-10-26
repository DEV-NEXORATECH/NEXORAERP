@extends('layouts.pdf', [
    'docTitle'       => 'SURAT PERJANJIAN KONTRAK KLIEN (CLIENT CONTRACT)',
    'docNumber'      => $contract->contract_number,
    'docDate'        => $contract->start_date ? \Carbon\Carbon::parse($contract->start_date)->format('d F Y') : now()->format('d F Y'),
    'docDueDate'     => $contract->end_date ? \Carbon\Carbon::parse($contract->end_date)->format('d F Y') : '—',
    'docStatus'      => $contract->status,
    'companySetting' => $companySetting ?? null,
    'preparedBy'     => auth()->user()->name ?? 'Account Manager',
    'reviewedBy'     => 'Legal / Commercial Manager',
    'approvedBy'     => 'Direktur Utama',
])

@section('content')
<table class="table-folk doc-table" style="margin-top: 0; margin-bottom: 16px;">
    <tr>
        <td style="width: 50%;">
            <div style="font-size: 8pt; color: #555; text-transform: uppercase; font-weight: bold;">Pihak Klien / Mitra:</div>
            <div style="font-size: 11pt; font-weight: bold; margin-top: 2px;">{{ $contract->client->name ?? 'Klien' }}</div>
            @if($contract->client && $contract->client->address)
                <div style="font-size: 8.5pt; color: #444; margin-top: 2px;">{{ $contract->client->address }}</div>
            @endif
            @if($contract->client && $contract->client->email)
                <div style="font-size: 8pt; color: #666; margin-top: 1px;">Email: {{ $contract->client->email }}</div>
            @endif
        </td>
        <td style="width: 50%;">
            <div style="font-size: 8pt; color: #555; text-transform: uppercase; font-weight: bold;">Masa Berlaku Kontrak:</div>
            <div style="font-size: 9.5pt; font-weight: bold; margin-top: 2px;">
                {{ $contract->start_date ? \Carbon\Carbon::parse($contract->start_date)->format('d F Y') : '-' }} s/d {{ $contract->end_date ? \Carbon\Carbon::parse($contract->end_date)->format('d F Y') : '-' }}
            </div>
            <div style="font-size: 8.5pt; color: #444; margin-top: 2px;">Status: <strong style="text-transform: uppercase;">{{ $contract->status }}</strong></div>
            @if($contract->reminder_date)
                <div style="font-size: 8pt; color: #666; margin-top: 2px;">Pengingat Review: {{ \Carbon\Carbon::parse($contract->reminder_date)->format('d F Y') }}</div>
            @endif
        </td>
    </tr>
</table>

<table class="table-folk doc-table">
    <thead>
        <tr>
            <th style="width: 5%;" class="text-center">No</th>
            <th style="width: 65%;">Ruang Lingkup & Perjanjian Kontrak Klien</th>
            <th style="width: 30%;" class="text-right">Nilai Kontrak (IDR)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="text-center">1</td>
            <td>
                <div class="text-bold">{{ $contract->title }}</div>
                <div style="font-size: 8pt; color: #444;">No. Kontrak: {{ $contract->contract_number }}</div>
                @if($contract->notes)
                    <div style="font-size: 8pt; color: #666; margin-top: 4px; font-style: italic;">Catatan: {{ $contract->notes }}</div>
                @endif
            </td>
            <td class="text-right text-bold" style="font-size: 11pt;">Rp {{ number_format($contract->amount, 0, ',', '.') }}</td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" class="text-right text-bold">Total Nilai Kontrak:</td>
            <td class="text-right text-bold" style="font-size: 11pt; color: #002875;">Rp {{ number_format($contract->amount, 0, ',', '.') }}</td>
        </tr>
    </tfoot>
</table>
@endsection
