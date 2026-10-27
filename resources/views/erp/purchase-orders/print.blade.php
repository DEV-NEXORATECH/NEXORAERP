@extends('layouts.pdf', [
    'docTitle'       => 'SURAT PESANAN PEMBELIAN (PURCHASE ORDER)',
    'docNumber'      => $order->number,
    'docDate'        => $order->order_date,
    'docDueDate'     => $order->expected_delivery_date ?? '—',
    'docStatus'      => $order->status,
    'companySetting' => $companySetting ?? null,
    'preparedBy'     => auth()->user()->name ?? 'Procurement Staff',
    'reviewedBy'     => 'Procurement Manager',
    'approvedBy'     => 'Direktur Operasional',
])

@section('content')
{{-- Vendor Info Table --}}
<table class="table-folk doc-table" style="margin-top: 0; margin-bottom: 16px;">
    <tr>
        <td style="width: 50%;">
            <div style="font-size: 8pt; color: #555; text-transform: uppercase; font-weight: bold;">Vendor / Supplier:</div>
            <div style="font-size: 11pt; font-weight: bold; margin-top: 2px;">{{ $order->vendor?->name ?? 'Vendor Umum' }}</div>
            @if($order->vendor?->address)
                <div style="font-size: 8.5pt; color: #444; margin-top: 2px;">{{ $order->vendor?->address }}</div>
            @endif
            @if($order->vendor?->email || $order->vendor?->phone)
                <div style="font-size: 8.5pt; color: #444;">{{ $order->vendor?->email }} | {{ $order->vendor?->phone }}</div>
            @endif
        </td>
        <td style="width: 50%;">
            <div style="font-size: 8pt; color: #555; text-transform: uppercase; font-weight: bold;">Informasi Pengiriman & Termin:</div>
            <div style="font-size: 10pt; font-weight: bold; margin-top: 2px;">Gudang Utama Nexora360</div>
            <div style="font-size: 8.5pt; color: #444; margin-top: 2px;">Syarat Pembayaran: {{ $order->vendor?->payment_terms ?? 'Net 30 Days' }}</div>
            @if($order->requisition)
                <div style="font-size: 8.5pt; color: #444;">Referensi PR: {{ $order->requisition?->number }}</div>
            @endif
        </td>
    </tr>
</table>

{{-- Line Items Table --}}
<table class="table-folk doc-table">
    <thead>
        <tr>
            <th style="width: 5%;" class="text-center">No</th>
            <th style="width: 45%;">Deskripsi Barang / Jasa</th>
            <th style="width: 15%;" class="text-right">Kuantitas</th>
            <th style="width: 15%;" class="text-right">Harga Satuan</th>
            <th style="width: 20%;" class="text-right">Subtotal (IDR)</th>
        </tr>
    </thead>
    <tbody>
        @forelse($order->items as $idx => $item)
            <tr>
                <td class="text-center">{{ $idx + 1 }}</td>
                <td>
                    <div class="text-bold">{{ $item->product?->name ?? 'Item PO' }}</div>
                    @if($item->notes)
                        <div style="font-size: 8pt; color: #555; margin-top: 2px;">Catatan: {{ $item->notes }}</div>
                    @endif
                </td>
                <td class="text-right">{{ number_format($item->quantity, 0, ',', '.') }} {{ $item->product?->unit ?? 'Unit' }}</td>
                <td class="text-right">Rp {{ number_format((float)$item->unit_price, 0, ',', '.') }}</td>
                <td class="text-right text-bold">Rp {{ number_format((float)($item->quantity * $item->unit_price), 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr>
                <td class="text-center">1</td>
                <td>Pesanan Pembelian {{ $order->number }}</td>
                <td class="text-right">1 Lot</td>
                <td class="text-right">Rp {{ number_format((float)$order->total_amount, 0, ',', '.') }}</td>
                <td class="text-right text-bold">Rp {{ number_format((float)$order->total_amount, 0, ',', '.') }}</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4" class="text-right text-bold">TOTAL PEMBELIAN (IDR):</td>
            <td class="text-right text-bold" style="font-size: 11pt;">Rp {{ number_format((float)$order->total_amount, 0, ',', '.') }}</td>
        </tr>
    </tfoot>
</table>

@if($order->notes)
    <div style="margin-top: 14px; padding: 10px; border: 1px solid #CCC; background: #FAFAFA; font-size: 8.5pt;">
        <strong>Instruksi Pengiriman / Catatan:</strong><br>
        {{ $order->notes }}
    </div>
@endif
@endsection

