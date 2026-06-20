@extends('layouts.erp', ['activePage' => 'procurement', 'pageTitle' => 'Procurement Suite'])

@section('content')
<section class="module-detail-page">
    <div class="module-detail-hero">
        <div class="module-detail-copy">
            <a class="module-back-link" href="{{ route('modules.index') }}">Back to Hub</a>
            <div class="module-title-row">
                <span class="module-title-icon">{!! $icon('projects') !!}</span>
                <div>
                    <span class="module-eyebrow">NEXORA ERP Module</span>
                    <h1>Procurement Suite</h1>
                </div>
            </div>
            <p>Ringkasan procurement: vendor, purchase requisition, purchase order, receipt, dan contract.</p>
        </div>
        <div class="module-count">
            <strong>5</strong>
            <span>Sub Module</span>
        </div>
    </div>
</section>

<section class="grid cards section">
    <div class="stat-card"><div class="stat-card-label">Vendors</div><div class="stat-card-metric">{{ $vendors->total() }}</div><div class="muted">Supplier master</div></div>
    <div class="stat-card"><div class="stat-card-label">Purchase Request</div><div class="stat-card-metric">{{ $requisitions->total() }}</div><div class="muted">Request pembelian</div></div>
    <div class="stat-card"><div class="stat-card-label">Purchase Order</div><div class="stat-card-metric">{{ $orders->total() }}</div><div class="muted">Order aktif</div></div>
    <div class="stat-card"><div class="stat-card-label">Contracts</div><div class="stat-card-metric">{{ $contracts->total() }}</div><div class="muted">Kontrak vendor</div></div>
</section>

<section class="module-sub-grid">
    <a class="module-sub-card" href="{{ route('vendors.index') }}"><div class="module-sub-main"><span class="module-sub-icon">{!! $icon('users') !!}</span><div><h3>Vendor Management</h3><p>Kelola master vendor.</p></div></div><span class="module-sub-action">Open</span></a>
    <a class="module-sub-card" href="{{ route('purchase-requisitions.index') }}"><div class="module-sub-main"><span class="module-sub-icon">{!! $icon('proposal') !!}</span><div><h3>Purchase Requisition</h3><p>Request dan approval pembelian.</p></div></div><span class="module-sub-action">Open</span></a>
    <a class="module-sub-card" href="{{ route('purchase-orders.index') }}"><div class="module-sub-main"><span class="module-sub-icon">{!! $icon('projects') !!}</span><div><h3>Purchase Order</h3><p>Order ke vendor.</p></div></div><span class="module-sub-action">Open</span></a>
    <a class="module-sub-card" href="{{ route('goods-receipts.index') }}"><div class="module-sub-main"><span class="module-sub-icon">{!! $icon('audit') !!}</span><div><h3>Goods Receipt</h3><p>Verifikasi barang/jasa diterima.</p></div></div><span class="module-sub-action">Open</span></a>
    <a class="module-sub-card" href="{{ route('procurement-contracts.index') }}"><div class="module-sub-main"><span class="module-sub-icon">{!! $icon('invoice') !!}</span><div><h3>Procurement Contract</h3><p>Tracking kontrak dan renewal.</p></div></div><span class="module-sub-action">Open</span></a>
</section>
@endsection
