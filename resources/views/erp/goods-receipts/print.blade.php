@extends('layouts.pdf', [
    'docTitle'       => 'SURAT PENERIMAAN BARANG (GOODS RECEIPT NOTE)',
    'docNumber'      => 'GRN-' . str_pad($receipt->id, 5, '0', STR_PAD_LEFT),
    'docDate'        => $receipt->receipt_date ? \Carbon\Carbon::parse($receipt->receipt_date)->format('d F Y') : now()->format('d F Y'),
    'docDueDate'     => '—',
    'docStatus'      => $receipt->status,
    'companySetting' => $companySetting ?? null,
    'preparedBy'     => auth()->user()->name ?? 'Gudang / Warehouse Dept',
    'reviewedBy'     => 'Kepala Gudang',
    'approvedBy'     => 'Manajer Logistik',
])

@section('content')
<table class="table-folk doc-table" style="margin-top: 0; margin-bottom: 16px;">
    <tr>
        <td style="width: 50%;">
            <div style="font-size: 8pt; color: #555; text-transform: uppercase; font-weight: bold;">Penerimaan Dari Vendor:</div>
            <div style="font-size: 11pt; font-weight: bold; margin-top: 2px;">{{ $receipt->purchaseOrder?->vendor?->name ?? 'Vendor Terdaftar' }}</div>
            <div style="font-size: 8.5pt; color: #444; margin-top: 2px;">Ref. Purchase Order: {{ $receipt->purchaseOrder?->number ?? '-' }}</div>
        </td>
        <td style="width: 50%;">
            <div style="font-size: 8pt; color: #555; text-transform: uppercase; font-weight: bold;">Keterangan / Kondisi Barang:</div>
            <div style="font-size: 9.5pt; font-weight: bold; margin-top: 2px;">Status: <span style="text-transform: uppercase;">{{ $receipt->status }}</span></div>
            @if($receipt->notes)
                <div style="font-size: 8.5pt; color: #444; margin-top: 2px;">Catatan: {{ $receipt->notes }}</div>
            @endif
        </td>
    </tr>
</table>

<table class="table-folk doc-table">
    <thead>
        <tr>
            <th style="width: 5%;" class="text-center">No</th>
            <th style="width: 50%;">Nama Barang / Produk</th>
            <th style="width: 20%;" class="text-center">Kuantitas Diterima</th>
            <th style="width: 25%;" class="text-center">Status Fisik Barang</th>
        </tr>
    </thead>
    <tbody>
        @if($receipt->purchaseOrder && $receipt->purchaseOrder?->items->count())
            @foreach($receipt->purchaseOrder?->items as $idx => $item)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>
                        <div class="text-bold">{{ $item->product?->name ?? 'Barang PO' }}</div>
                        @if($item->product && $item->product?->sku)
                            <div style="font-size: 8pt; color: #555;">SKU: {{ $item->product?->sku }}</div>
                        @endif
                    </td>
                    <td class="text-center text-bold">{{ number_format($item->quantity, 0, ',', '.') }} {{ $item->product?->unit ?? 'Unit' }}</td>
                    <td class="text-center" style="color: #15803D; font-weight: bold;">Lengkap & Baik</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="text-center">1</td>
                <td>
                    <div class="text-bold">Penerimaan Barang PO: {{ $receipt->purchaseOrder?->number ?? '-' }}</div>
                </td>
                <td class="text-center text-bold">1 Lot</td>
                <td class="text-center" style="color: #15803D; font-weight: bold;">Diterima Lengkap</td>
            </tr>
        @endif
    </tbody>
</table>
@endsection

