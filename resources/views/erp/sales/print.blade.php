@extends('layouts.pdf', [
    'docTitle'       => 'PROPOSAL PENAWARAN',
    'docNumber'      => $proposal->number,
    'docDate'        => $proposal->created_at?->format('Y-m-d') ?? date('Y-m-d'),
    'docDueDate'     => $proposal->valid_until ?? '—',
    'docStatus'      => $proposal->status,
    'companySetting' => $companySetting ?? null,
    'preparedBy'     => 'Sales & Business Dept',
    'reviewedBy'     => 'Commercial Lead',
    'approvedBy'     => $proposal->project->client ?? 'Client PIC',
])

@section('content')
{{-- Metadata Table --}}
<table class="doc-table" style="margin-top: 0; margin-bottom: 16px;">
    <tr>
        <td style="width: 50%;">
            <div style="font-size: 8pt; color: #555; text-transform: uppercase; font-weight: bold;">Ditujukan Kepada:</div>
            <div style="font-size: 11pt; font-weight: bold; margin-top: 2px;">{{ $proposal->project->client }}</div>
            <div style="font-size: 8.5pt; color: #444; margin-top: 2px;">Proyek: {{ $proposal->project->code }} — {{ $proposal->project->name }}</div>
        </td>
        <td style="width: 50%;">
            <div style="font-size: 8pt; color: #555; text-transform: uppercase; font-weight: bold;">Rincian Penawaran:</div>
            <div style="font-size: 10pt; font-weight: bold; margin-top: 2px;">{{ $proposal->title }}</div>
            <div style="font-size: 8.5pt; color: #444; margin-top: 2px;">Masa Berlaku: {{ $proposal->valid_until ?? '30 Hari kalender' }}</div>
            <div style="font-size: 8.5pt; color: #444;">Status: <strong>{{ strtoupper($proposal->status) }}</strong></div>
        </td>
    </tr>
</table>

{{-- Commercial Summary Table --}}
<table class="doc-table">
    <thead>
        <tr>
            <th style="width: 5%;" class="text-center">No</th>
            <th style="width: 65%;">Ruang Lingkup & Deskripsi Proposal</th>
            <th style="width: 30%;" class="text-right">Nilai Investasi (IDR)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="text-center">1</td>
            <td>
                <div class="text-bold">{{ $proposal->title }}</div>
                @if($proposal->scope)
                    <div style="font-size: 8pt; color: #444; margin-top: 4px; line-height: 1.5;">{!! nl2br(e($proposal->scope)) !!}</div>
                @endif
            </td>
            <td class="text-right text-bold">Rp {{ number_format((float)$proposal->amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="2" class="text-right text-bold">TOTAL PENAWARAN (EXCL. TAX)</td>
            <td class="text-right text-bold" style="font-size: 10pt;">Rp {{ number_format((float)$proposal->amount, 0, ',', '.') }}</td>
        </tr>
    </tbody>
</table>

{{-- Terms & Conditions --}}
<div style="margin-top: 16px; padding: 10px; border: 1px solid #ddd; background-color: #fafafa; font-size: 8pt; line-height: 1.5;">
    <strong>Ketentuan Penawaran:</strong>
    <ol style="margin: 4px 0 0 16px; padding: 0;">
        <li>Harga penawaran di atas belum termasuk PPN 11% kecuali dinyatakan lain secara tertulis.</li>
        <li>Penawaran ini mengikat sampai tanggal jatuh tempo validitas yang tercantum pada dokumen.</li>
        <li>Persetujuan proposal dilakukan dengan penandatanganan dokumen ini atau penerbitan Purchase Order / SPK resmi.</li>
    </ol>
</div>
@endsection
