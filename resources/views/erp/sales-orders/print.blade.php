@extends('layouts.pdf', [
    'docTitle'       => 'SALES ORDER (SURAT PESANAN)',
    'docNumber'      => $order->number,
    'docDate'        => $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('d F Y') : now()->format('d F Y'),
    'docDueDate'     => '—',
    'docStatus'      => $order->status,
    'companySetting' => $companySetting ?? null,
    'preparedBy'     => auth()->user()->name ?? 'Sales Dept',
    'reviewedBy'     => 'Sales Manager',
    'approvedBy'     => 'Direktur Komersial',
])

@section('content')
<table class="table-folk doc-table" style="margin-top: 0; margin-bottom: 16px;">
    <tr>
        <td style="width: 50%;">
            <div style="font-size: 8pt; color: #555; text-transform: uppercase; font-weight: bold;">Pemesan / Customer:</div>
            <div style="font-size: 11pt; font-weight: bold; margin-top: 2px;">{{ $order->client?->name ?? 'Pelanggan Umum' }}</div>
            @if($order->client && $order->client?->address)
                <div style="font-size: 8.5pt; color: #444; margin-top: 2px;">{{ $order->client?->address }}</div>
            @endif
            @if($order->client && ($order->client?->email || $order->client?->phone))
                <div style="font-size: 8.5pt; color: #444;">{{ $order->client?->email }} | {{ $order->client?->phone }}</div>
            @endif
        </td>
        <td style="width: 50%;">
            <div style="font-size: 8pt; color: #555; text-transform: uppercase; font-weight: bold;">Informasi Pesanan:</div>
            <div style="font-size: 10pt; font-weight: bold; margin-top: 2px;">{{ $order->title }}</div>
            @if($order->proposal)
                <div style="font-size: 8.5pt; color: #444; margin-top: 2px;">Ref. Proposal: {{ $order->proposal?->number ?? '-' }}</div>
            @endif
            @if($order->notes)
                <div style="font-size: 8.5pt; color: #444;">Catatan: {{ $order->notes }}</div>
            @endif
        </td>
    </tr>
</table>

<table class="table-folk doc-table">
    <thead>
        <tr>
            <th style="width: 5%;" class="text-center">No</th>
            <th style="width: 45%;">Nama Produk / Layanan</th>
            <th style="width: 15%;" class="text-center">Kuantitas</th>
            <th style="width: 15%;" class="text-right">Harga Satuan (IDR)</th>
            <th style="width: 20%;" class="text-right">Total (IDR)</th>
        </tr>
    </thead>
    <tbody>
        @forelse($order->items as $idx => $item)
            @php $subtotal = (float)$item->quantity * (float)$item->unit_price; @endphp
            <tr>
                <td class="text-center">{{ $idx + 1 }}</td>
                <td>
                    <div class="text-bold">{{ $item->product?->name ?? 'Item Produk' }}</div>
                    @if($item->notes)
                        <div style="font-size: 8pt; color: #444;">{{ $item->notes }}</div>
                    @endif
                </td>
                <td class="text-center">{{ number_format($item->quantity, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                <td class="text-right text-bold">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr>
                <td class="text-center">1</td>
                <td>
                    <div class="text-bold">{{ $order->title }}</div>
                </td>
                <td class="text-center">1</td>
                <td class="text-right">Rp {{ number_format($order->amount, 0, ',', '.') }}</td>
                <td class="text-right text-bold">Rp {{ number_format($order->amount, 0, ',', '.') }}</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4" class="text-right text-bold">Total Nilai Pesanan:</td>
            <td class="text-right text-bold" style="font-size: 11pt;">Rp {{ number_format($order->amount, 0, ',', '.') }}</td>
        </tr>
    </tfoot>
</table>
@endsection

