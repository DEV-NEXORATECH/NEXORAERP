@extends('layouts.erp', ['activePage' => 'purchase-order-create', 'pageTitle' => 'Buat Purchase Order'])

@section('content')
<section class="section">
    <div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#0B2345]/5">
        <div class="mb-8 border-b border-[#d7e3ef] pb-5">
            <h2 class="mb-1">Buat Purchase Order</h2>
            <p class="muted">Buat order ke vendor.</p>
        </div>
        <form method="post" action="{{ route('purchase-orders.store') }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
            @csrf
            <div class="grid gap-1.5 md:col-span-2"><label>PR Reference</label>
                <select name="purchase_requisition_id">
                    <option value="">Direct PO (tanpa PR)</option>
                    @foreach($allRequisitions as $pr)<option value="{{ $pr->id }}">{{ $pr->number }} - {{ $pr->title }}</option>@endforeach
                </select>
            </div>
            <div class="grid gap-1.5"><label>Vendor</label>
                <select name="vendor_id">
                    <option value="">Pilih vendor</option>
                    @foreach($allVendors as $vendor)<option value="{{ $vendor->id }}">{{ $vendor->name }}</option>@endforeach
                </select>
            </div>
            <div class="grid gap-1.5"><label>Tanggal Order</label><input name="order_date" type="date" value="{{ now()->toDateString() }}" required></div>
            <div class="grid gap-1.5"><label>Amount</label><input name="amount" type="number" min="0" required></div>
            <div class="grid gap-1.5"><label>Status</label>
                <select name="status">
                    <option value="ordered">Ordered</option>
                    <option value="partial_received">Partial Received</option>
                    <option value="received">Received</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="grid gap-1.5 md:col-span-2"><label>Catatan</label><textarea name="notes" rows="3"></textarea></div>
            <div class="md:col-span-2 grid gap-1.5">
                <label>Item Produk</label>
                <table class="w-full text-sm" id="items-table">
                    <thead>
                        <tr class="text-left text-xs uppercase text-[#0B2345]/60">
                            <th class="py-1 pr-2">Produk</th>
                            <th class="py-1 pr-2 w-28">Qty</th>
                            <th class="py-1 pr-2 w-40">Harga Satuan</th>
                            <th class="py-1 pr-2 w-40">Subtotal</th>
                            <th class="py-1 w-10"></th>
                        </tr>
                    </thead>
                    <tbody id="items-body">
                        <tr class="item-row">
                            <td class="py-1 pr-2">
                                <select name="items[0][product_id]" class="item-product">
                                    <option value="">— Pilih produk —</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->cost }}">{{ $product->code }} - {{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="py-1 pr-2"><input type="number" step="any" min="0" class="item-qty" name="items[0][quantity]" value="1"></td>
                            <td class="py-1 pr-2"><input type="number" step="any" min="0" class="item-price" name="items[0][unit_price]"></td>
                            <td class="py-1 pr-2 item-subtotal">0</td>
                            <td class="py-1"><button type="button" class="btn-remove-row text-red-600" title="Hapus baris">×</button></td>
                        </tr>
                    </tbody>
                </table>
                <div class="mt-1 flex items-center justify-between">
                    <button type="button" class="text-sm font-bold text-[#0B2345]" id="btn-add-row">+ Tambah Baris</button>
                    <span class="text-sm font-black">Total: <span id="items-total">0</span></span>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-3 md:col-span-2">
                <button class="btn">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Simpan PO
                </button>
                <a class="button ghost" href="{{ route('purchase-orders.index') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali
                </a>
            </div>
        </form>
    </div>
</section>

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
        row.querySelector('.item-subtotal').textContent = sub.toLocaleString('id-ID', { maximumFractionDigits: 2 });
        return sub;
    }

    function recalc() {
        let total = 0;
        body.querySelectorAll('.item-row').forEach((row) => { total += subtotal(row); });
        totalEl.textContent = total.toLocaleString('id-ID', { maximumFractionDigits: 2 });
        if (amountInput) amountInput.value = total ? total.toFixed(2) : '';
    }

    function newRow() {
        const tpl = document.createElement('tr');
        tpl.className = 'item-row';
        tpl.innerHTML = `
            <td class="py-1 pr-2"><select class="item-product">
                <option value="">— Pilih produk —</option>
                ${productOptions.map(p => `<option value="${p.id}" data-price="${p.price}">${p.code} - ${p.name}</option>`).join('')}
            </select></td>
            <td class="py-1 pr-2"><input type="number" step="any" min="0" class="item-qty" value="1"></td>
            <td class="py-1 pr-2"><input type="number" step="any" min="0" class="item-price"></td>
            <td class="py-1 pr-2 item-subtotal">0</td>
            <td class="py-1"><button type="button" class="btn-remove-row text-red-600" title="Hapus baris">×</button></td>`;
        body.appendChild(tpl);
        reindex();
    }

    body.addEventListener('click', (e) => {
        if (e.target.closest('.btn-remove-row')) {
            e.target.closest('.item-row').remove();
            reindex();
            recalc();
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
