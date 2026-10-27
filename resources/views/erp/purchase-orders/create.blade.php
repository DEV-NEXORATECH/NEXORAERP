@extends('layouts.erp', ['activePage' => 'purchase-orders', 'pageTitle' => 'Buat Purchase Order'])

@section('content')
<div class="erp-form-page space-y-5 max-w-5xl">
    <x-erp.page-header
        title="Buat Purchase Order (PO) Baru"
        subtitle="Terbitkan surat pesanan pembelian resmi kepada vendor mitra dengan alokasi item produk dan kuantitas barang."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Procurement', 'url' => route('procurement.index')], ['label' => 'Purchase Orders', 'url' => route('purchase-orders.index')], ['label' => 'Buat PO']]"
    />

    <div class="rounded-lg border border-[#D1D5DB] bg-white p-6 shadow-xs">
        <form method="post" action="{{ route('purchase-orders.store') }}" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Referensi Purchase Requisition (PR)
                    </label>
                    <select name="purchase_requisition_id"
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                        <option value="">Direct PO (Pengadaan langsung tanpa PR)</option>
                        @foreach($allRequisitions as $pr)
                            <option value="{{ $pr->id }}" @selected(old('purchase_requisition_id') == $pr->id)>
                                {{ $pr->number }} - {{ $pr->title }} ({{ $pr->department->name ?? 'General' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Pemasok / Vendor Terpilih <span class="text-red-500">*</span>
                    </label>
                    <select name="vendor_id" required
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                        <option value="">-- Pilih Vendor --</option>
                        @foreach($allVendors as $vendor)
                            <option value="{{ $vendor->id }}" @selected(old('vendor_id') == $vendor->id)>
                                {{ $vendor->name }} ({{ $vendor->category ?: 'General' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Tanggal Penerbitan PO <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="order_date" value="{{ old('order_date', now()->toDateString()) }}" required
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Status Pengadaan PO <span class="text-red-500">*</span>
                    </label>
                    <select name="status" required
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                        <option value="ordered" @selected(old('status') === 'ordered')>Ordered (Telah Dipesan ke Vendor)</option>
                        <option value="partial_received" @selected(old('status') === 'partial_received')>Partial Received (Diterima Sebagian)</option>
                        <option value="received" @selected(old('status') === 'received')>Received (Selesai Diterima)</option>
                        <option value="cancelled" @selected(old('status') === 'cancelled')>Cancelled (Dibatalkan)</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Catatan & Instruksi Deliveri
                    </label>
                    <textarea name="notes" rows="2" placeholder="Instruksi pengiriman, lokasi gudang tujuan, atau syarat penerimaan..."
                        class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">{{ old('notes') }}</textarea>
                </div>
            </div>

            {{-- Line Items Table --}}
            <div class="border-t border-[#D1D5DB] pt-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-[#002875]">Rincian Item Produk Pembelian</h3>
                    <button type="button" id="btn-add-row" class="inline-flex items-center gap-1 rounded bg-slate-100 px-2.5 py-1 text-xs font-semibold text-[#002875] hover:bg-slate-200 transition-colors">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                        Tambah Baris Produk
                    </button>
                </div>

                <div class="border border-[#D1D5DB] rounded-md overflow-hidden">
                    <table class="table-folk w-full text-left text-xs" id="items-table">
                        <thead class="bg-slate-50 border-b border-[#D1D5DB]">
                            <tr>
                                <th class="px-3 py-2.5 font-semibold text-slate-600 uppercase">Item Produk</th>
                                <th class="px-3 py-2.5 font-semibold text-slate-600 uppercase w-28">Kuantitas</th>
                                <th class="px-3 py-2.5 font-semibold text-slate-600 uppercase w-44">Harga Beli Satuan (Rp)</th>
                                <th class="px-3 py-2.5 font-semibold text-slate-600 uppercase text-right w-44">Subtotal (Rp)</th>
                                <th class="px-3 py-2.5 w-10"></th>
                            </tr>
                        </thead>
                        <tbody id="items-body" class="divide-y divide-slate-100">
                            <tr class="item-row">
                                <td class="px-3 py-2">
                                    <select name="items[0][product_id]" class="item-product w-full rounded border border-[#D1D5DB] bg-white px-2 py-1 text-xs text-slate-900 focus:border-[#002875] focus:outline-none">
                                        <option value="">-- Pilih produk --</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-price="{{ $product->cost }}">{{ $product->code }} - {{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-3 py-2">
                                    <input type="number" step="any" min="0" class="item-qty w-full rounded border border-[#D1D5DB] bg-white px-2 py-1 text-xs text-slate-900 focus:border-[#002875] focus:outline-none" name="items[0][quantity]" value="1">
                                </td>
                                <td class="px-3 py-2">
                                    <input type="number" step="any" min="0" class="item-price w-full rounded border border-[#D1D5DB] bg-white px-2 py-1 text-xs text-slate-900 focus:border-[#002875] focus:outline-none" name="items[0][unit_price]">
                                </td>
                                <td class="px-3 py-2 text-right font-bold text-slate-900 item-subtotal">0</td>
                                <td class="px-3 py-2 text-center">
                                    <button data-action="delete" type="button" class="erp-action-button btn-remove-row text-red-500 hover:text-red-700 font-bold text-base" title="Hapus baris">×</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 flex items-center justify-between p-3 rounded-md bg-slate-50 border border-[#D1D5DB]">
                    <span class="text-xs font-semibold text-slate-600">Total Nilai Purchase Order:</span>
                    <div class="text-base font-bold text-[#002875]">
                        Rp <span id="items-total">0</span>
                    </div>
                </div>
                <input type="hidden" name="amount" value="{{ old('amount', 0) }}" required>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-[#D1D5DB]">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-4 py-2 text-xs font-semibold text-white shadow-xs hover:bg-[#001B50] transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                    Terbitkan Purchase Order
                </button>
                <a href="{{ route('purchase-orders.index') }}" class="inline-flex items-center gap-1.5 rounded-md border border-[#D1D5DB] bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-[#F3F4F6] transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    const body = document.getElementById('items-body');
    const totalEl = document.getElementById('items-total');
    const amountInput = document.querySelector('input[name="amount"]');
    const productOptions = @json($productOptions);

    function reindex() {
        body.querySelectorAll('.item-row').forEach((row, i) => {
            row.querySelector('.item-product').name = `items[${i}][product_id]`;
            row.querySelector('.item-qty').name = `items[${i}][quantity]`;
            row.querySelector('.item-price').name = `items[${i}][unit_price]`;
        });
    }

    function subtotal(row) {
        const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
        const price = parseFloat(row.querySelector('.item-price').value) || 0;
        const sub = qty * price;
        row.querySelector('.item-subtotal').textContent = sub.toLocaleString('id-ID', { maximumFractionDigits: 0 });
        return sub;
    }

    function recalc() {
        let total = 0;
        body.querySelectorAll('.item-row').forEach((row) => { total += subtotal(row); });
        totalEl.textContent = total.toLocaleString('id-ID', { maximumFractionDigits: 0 });
        if (amountInput) amountInput.value = total ? total.toFixed(2) : 0;
    }

    function newRow() {
        const tpl = document.createElement('tr');
        tpl.className = 'item-row';
        tpl.innerHTML = `
            <td class="px-3 py-2"><select class="item-product w-full rounded border border-[#D1D5DB] bg-white px-2 py-1 text-xs text-slate-900 focus:border-[#002875] focus:outline-none">
                <option value="">-- Pilih produk --</option>
                ${productOptions.map(p => `<option value="${p.id}" data-price="${p.cost}">${p.code} - ${p.name}</option>`).join('')}
            </select></td>
            <td class="px-3 py-2"><input type="number" step="any" min="0" class="item-qty w-full rounded border border-[#D1D5DB] bg-white px-2 py-1 text-xs text-slate-900 focus:border-[#002875] focus:outline-none" value="1"></td>
            <td class="px-3 py-2"><input type="number" step="any" min="0" class="item-price w-full rounded border border-[#D1D5DB] bg-white px-2 py-1 text-xs text-slate-900 focus:border-[#002875] focus:outline-none"></td>
            <td class="px-3 py-2 text-right font-bold text-slate-900 item-subtotal">0</td>
            <td class="px-3 py-2 text-center"><button data-action="delete" type="button" class="erp-action-button btn-remove-row text-red-500 hover:text-red-700 font-bold text-base" title="Hapus baris">×</button></td>`;
        body.appendChild(tpl);
        reindex();
    }

    body.addEventListener('click', (e) => {
        if (e.target.closest('.btn-remove-row')) {
            if (body.querySelectorAll('.item-row').length > 1) {
                e.target.closest('.item-row').remove();
                reindex();
                recalc();
            }
        }
    });

    body.addEventListener('change', (e) => {
        if (e.target.classList.contains('item-product')) {
            const price = e.target.selectedOptions[0]?.dataset?.price || 0;
            e.target.closest('.item-row').querySelector('.item-price').value = price;
        }
        recalc();
    });

    body.addEventListener('input', (e) => {
        if (e.target.classList.contains('item-qty') || e.target.classList.contains('item-price')) recalc();
    });

    document.getElementById('btn-add-row').addEventListener('click', newRow);
    recalc();
})();
</script>
@endsection
